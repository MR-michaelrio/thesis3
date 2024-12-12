<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AddressEmployee;
use App\Models\User;
use App\Models\Shift;
use App\Models\AssignShift;
use App\Models\AssignLeave;
use App\Models\Leave;
use App\Models\Department;
use App\Models\DepartmentPosition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        if(Auth::user()->role == "supervisor"){
            $employees = Employee::with(['user.department', 'user.position'])
                            ->whereHas('user', function($query) {
                                $query->where('id_company', Auth::user()->id_company)
                                    ->where('id_department', Auth::user()->id_department);
                            })
                            ->get();

        }else{
            $employees = Employee::with(['user.department', 'user.position'])->where("id_company",Auth::user()->id_company)->get();
        }
        return view('employee.employee-data', compact('employees'));
    }

    public function create()
    {
        $department = Department::where("id_company",Auth::user()->id_company)->get();
        $departmentPosition = DepartmentPosition::where("id_company",Auth::user()->id_company)->get();
        $user = User::where("role", "supervisor")->where("id_company",Auth::user()->id_company)->get();
        $shift = Shift::where("id_company",Auth::user()->id_company)->get();
        $leave = Leave::where("id_company", Auth::user()->id_company)->get();

        return view('employee.employee-add', compact('department', 'departmentPosition', 'user', 'shift', 'leave'));
    }

    public function store(Request $request)
    {
        try {
            // Buat data di tabel employee_address
            $address = AddressEmployee::create([
                'country' => $request['country'],
                'postal_code' => $request['postal_code'],
                'full_address' => $request['full_address'],
                'id_company' => Auth::user()->id_company,
            ]);
            
        
            // Buat data di tabel user
            $user = User::create([
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'id_department' => $request['id_department'],
                'id_department_position' => $request['id_department_position'],
                'supervisor' => $request['supervisor'] ?? null,
                'start_work' => $request['start_work'],
                'stop_work' => $request['stop_work'] ?? null,
                'role' => "employee",
                'phone' => $request['phone'],
                'emergency_name' => $request['emergency_name'],
                'emergency_relation' => $request['emergency_relation'],
                'emergency_phone' => $request['emergency_phone'],
                'id_company' => Auth::user()->id_company,
                'identification_number' => $request->identification_number
            ]);
        
            // Format tanggal lahir
            $dateOfBirth = Carbon::createFromFormat('d/m/Y', $request->date_of_birth)->format('Y-m-d');
        
            // Persiapkan data untuk tabel employee
            $employeeData = [
                'profile_picture' => null,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'gender' => $request->input('gender'),
                'marital' => $request->input('marital'),
                'religion' => $request->input('religion'),
                'place_of_birth' => $request->input('place_of_birth'),
                'date_of_birth' => $dateOfBirth,
                'id_address_employee' => $address->id_address_employee,
                'id_users' => $user->id_user,
                'id_company' => Auth::user()->id_company,
            ];

            if ($request->hasFile('profile_picture')) {
                // Get the file from the request
                $profile_picture = $request->file('profile_picture');
                
                // Generate the file name (you can add a timestamp to avoid conflicts)
                $profilePictureName = time() . '-' . $profile_picture->getClientOriginalName();
                
                // Move the file to the public/img directory
                $profile_picture->move(public_path('profile_picture'), $profilePictureName);
    
                // Update the company's logo with the new file name
                $employeeData['profile_picture'] = $profilePictureName;
            }
            // Buat data di tabel employee
            $employee = Employee::create($employeeData);
            
            if ($request->has('leaves')) {
                foreach ($request->input('leaves') as $leaveId) {
                    AssignLeave::create([
                        'id_employee' => $employee->id_employee,
                        'id_leave' => $leaveId,
                        'quota' => 0, // Set this to the appropriate value
                        'remaining' => 0, // Set this to the appropriate value
                        'id_company' => Auth::user()->id_company,
                    ]);
                }
            }

            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $dayMapping = [
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6,
                'sunday' => 7,
            ];

            foreach ($days as $day) {
                AssignShift::create([
                    'id_employee' => $employee->id_employee,
                    'id_shift' => $request->$day ?? null, // Jika kosong, biarkan null
                    'day' => $dayMapping[$day],
                ]);
            }

            // Redirect ke route employee.index dengan pesan sukses
            return redirect()->route('employee.index')->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
        
    }


    public function edit($id)
    {
        if (Auth::user()->role != "admin" && Auth::user()->employee->id_employee != $id) {
            return redirect()->route('home');
        }

        $employee = Employee::findOrFail($id);
        $assignShift = AssignShift::where('id_employee', $employee->id_employee)->get();
        $employeeLeaves = AssignLeave::where('id_employee', $employee->id_employee)->pluck('id_leave')->toArray();

        // Fetch other necessary data
        $department = Department::where("id_company", Auth::user()->id_company)->get();
        $departmentPosition = DepartmentPosition::where("id_company", Auth::user()->id_company)->get();
        $user = User::where("role", "supervisor")->where("id_company", Auth::user()->id_company)->get();
        $shift = Shift::where("id_company", Auth::user()->id_company)->get();
        $leave = Leave::where("id_company", Auth::user()->id_company)->get();

        $assignShiftByDay = $assignShift->groupBy('day');

        return view('employee.employee-edit', compact('leave','employee', 'employeeLeaves', 'assignShiftByDay', 'department', 'departmentPosition', 'user', 'shift'));

    }

    public function update(Request $request, $id)
    {
        try {
            // Find the employee
            $employee = Employee::findOrFail($id);
        
            // Update employee details
            $employee->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'full_name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'gender' => $request->input('gender'),
                'marital' => $request->input('marital'),
                'religion' => $request->input('religion'),
                'place_of_birth' => $request->input('place_of_birth'),
                'date_of_birth' => Carbon::createFromFormat('d/m/Y', $request->input('date_of_birth'))->format('Y-m-d'),
            ]);
            // Update profile picture if it's provided
            if ($request->hasFile('profile_picture')) {
                $profile_picture = $request->file('profile_picture');
                $profilePictureName = time() . '-' . $profile_picture->getClientOriginalName();
                $profile_picture->move(public_path('profile_picture'), $profilePictureName);
                $employee->profile_picture = $profilePictureName;
                $employee->save();
            }

            // Update employee address if necessary
            $employeeAddress = AddressEmployee::findOrFail($employee->id_address_employee);
            $employeeAddress->update([
                'country' => $request->input('country'),
                'postal_code' => $request->input('postal_code'),
                'full_address' => $request->input('full_address'),
            ]);
        
            $user = User::findOrFail($employee->id_users);
        
            if(Auth::user()->role == "admin"){
                // Only update email if it's provided and not the same as the current one
                if ($request->has('email') && $request->email != $user->email) {
                    $user->email = $request->input('email');
                }

                // Only update password if provided
                if ($request->has('password')) {
                    $user->password = Hash::make($request->input('password'));
                }
            }else{
                if (!Hash::check($request->old_password, $user->password)) {
                    return redirect()->back()->with('error', 'Old password is incorrect.');
                }
                // Update password
                $user->password = Hash::make($request->new_password);
                $user->save();
            }

            $user->identification_number = $request->identification_number;
            // Save the user changes
            $user->save();
        
            if(Auth::user()->role == "admin"){
                AssignLeave::where('id_employee', $employee->id_employee)->delete();
        
                if ($request->has('leaves')) {
                    foreach ($request->input('leaves') as $leaveId) {
                        AssignLeave::create([
                            'id_employee' => $employee->id_employee,
                            'id_leave' => $leaveId,
                            'quota' => 0, 
                            'remaining' => 0,
                            'id_company' => Auth::user()->id_company,
                        ]);
                    }
                }
        
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $dayMapping = [
                    'monday' => 1,
                    'tuesday' => 2,
                    'wednesday' => 3,
                    'thursday' => 4,
                    'friday' => 5,
                    'saturday' => 6,
                    'sunday' => 7,
                ];
        
                foreach ($days as $day) {
                    AssignShift::updateOrCreate(
                        ['id_employee' => $employee->id_employee, 'day' => $dayMapping[$day]],
                        ['id_shift' => $request->$day ?? null]
                    );
                }
            }
            // Return success response
            return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update employee: ' . $e->getMessage());
        }
        
    }


    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully!');
    }
}
