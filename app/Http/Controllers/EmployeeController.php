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
use Illuminate\Support\Facades\DB;

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

        }else if(Auth::user()->role == "admin"){
            $employees = Employee::with(['user.department', 'user.position'])->where("id_company",Auth::user()->id_company)->get();
        }else{
            return redirect()->route('home');
        }
        return view('employee.employee-data', compact('employees'));
    }

    public function create()
    {
        $department = Department::where("id_company",Auth::user()->id_company)->get();
        $departmentPosition = DepartmentPosition::where("id_company",Auth::user()->id_company)->get();
        $user = User::where(function($query) {
            $query->where('role', 'supervisor')
                  ->orWhere('role', 'admin');
        })
        ->where('id_company', Auth::user()->id_company)
        ->get();
        $shift = Shift::where("id_company",Auth::user()->id_company)->get();
        $leave = Leave::where("id_company", Auth::user()->id_company)->get();

        return view('employee.employee-add', compact('department', 'departmentPosition', 'user', 'shift', 'leave'));
    }

    public function getDepartmentPositions($departmentId)
    {
        $positions = DepartmentPosition::where('id_department', $departmentId)
                                       ->where('id_company', Auth::user()->id_company)
                                       ->get();
    
        return response()->json($positions);
    }
    
    public function getSupervisorsByDepartment($departmentId)
    {
        // Fetch the department's supervisor(s) based on the selected department
        $department = Department::where("id_department",$departmentId)->first();
        $user = Employee::where("id_employee",$department->id_supervisor)->first();

        $supervisors = User::where("id_user",$user->id_users)->where(function($query) {
                                $query->where('role', 'supervisor')
                                    ->orWhere('role', 'admin');
                        })
                        ->where('id_company', Auth::user()->id_company)
                        ->with('employee') 
                        ->get();

        $supervisorsall = User::where(function($query) {
                            $query->where('role', 'supervisor')
                                ->orWhere('role', 'admin');
                    })
                    ->where('id_company', Auth::user()->id_company)
                    ->with('employee') 
                    ->get();
        
        return response()->json([
            'supervisors' => $supervisors,
            'supervisorsall' => $supervisorsall
        ]);
    }



    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'password' => 'required|string|min:8|regex:/[0-9]/',
            ]);
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return back()->with('error', 'The email address is already in use.');
            }
            // Buat data di tabel address_employee
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
                'status' => 'active'
            ];

            if ($request->hasFile('profile_picture')) {
                $profile_picture = $request->file('profile_picture');
                $profilePictureName = time() . '-' . $profile_picture->getClientOriginalName();
                $profile_picture->move(public_path('profile_picture'), $profilePictureName);
                $employeeData['profile_picture'] = $profilePictureName;
            }

            // Buat data di tabel employee
            $employee = Employee::create($employeeData);

            if ($request->has('leaves')) {
                foreach ($request->input('leaves') as $leaveId) {
                    $leave = Leave::where("id_leave",$leaveId)->first();
                    AssignLeave::create([
                        'id_employee' => $employee->id_employee,
                        'id_leave' => $leaveId,
                        'quota' => $leave->default_quota, 
                        'remaining' => $leave->default_quota,
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
                    'id_shift' => $request->$day ?? null,
                    'day' => $dayMapping[$day],
                ]);
            }

            DB::commit();
            return redirect()->route('employee.index')->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            // return back()->withInput()->withErrors($validator);

            return back()->withInput()->with('error', $e->getMessage());
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
        $user = User::where(function($query) {
            $query->where('role', 'supervisor')
                  ->orWhere('role', 'admin');
        })
        ->where('id_company', Auth::user()->id_company)
        ->get();
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
                    $existingUser = User::where('email', $request->email)->first();
                    if ($existingUser) {
                        return redirect()->back()->with('error', 'The email address is already in use by another user.');
                    }
                    $user->email = $request->input('email');
                }
                // Only update password if provided
                
                if(Auth::user()->employee->id_employee != $employee->id_employee){
                    
                    if ($request->password) {
                        $validated = $request->validate([
                            'password' => 'string|min:8|regex:/[0-9]/',
                        ]);
                        $user->password = Hash::make($request->input('password'));
                    }
                }else{
                    if ($request->new_password) {
                        $validated = $request->validate([
                            'new_password' => 'string|min:8|regex:/[0-9]/',
                        ]);
                        if (!Hash::check($request->old_password, $user->password)) {
                            return redirect()->back()->with('error', 'Old password is incorrect.');
                        }
                        // Update password
                        $user->password = Hash::make($request->new_password);
                    }
                }
                
            }else{
                

                if($request->new_password){
                    $validated = $request->validate([
                        'new_password' => 'required|string|min:8|regex:/[0-9]/',
                    ]);
                    if (!Hash::check($request->old_password, $user->password)) {
                        return redirect()->back()->with('error', 'Old password is incorrect.');
                    }
                    // Update password
                    $user->password = Hash::make($request->new_password);
                }
            }

            $user->start_work = $request->start_work;
            $user->stop_work = $request->stop_work;
            $user->identification_number = $request->identification_number;
            // Save the user changes
            $user->save();
        
            if(Auth::user()->role == "admin"){
                AssignLeave::where('id_employee', $employee->id_employee)->delete();
        
                if ($request->has('leaves')) {
                    foreach ($request->input('leaves') as $leaveId) {
                        $leave = Leave::where("id_leave",$leaveId)->first();
                        AssignLeave::create([
                            'id_employee' => $employee->id_employee,
                            'id_leave' => $leaveId,
                            'quota' => $leave->default_quota, 
                            'remaining' => $leave->default_quota,
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
            if(Auth::user()->role == "employee"){
                return redirect()->route('home')->with('success', 'Employee updated successfully!');
            }else{
                return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully!');
    }

    public function statusupdate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        // Update status employee
        $employee->status = $request->status;
        $employee->save();

        // Return response for AJAX
        return response()->json(['message' => 'Status updated successfully']);
    }

    public function getDepartmentDetails(Request $request)
    {
        $departmentId = $request->input('department_id');
        
        // Get department information
        $department = Department::where('id_department', $departmentId)->first();
    
        // Get the supervisor for the department
        $supervisor = Employee::where('id_employee', $department->id_supervisor)->first();
        
        // Fetch positions related to the selected department
        $positions = DepartmentPosition::where('id_department', $departmentId)->get();
    
        // Fetch supervisors for the selected department
        $supervisors = User::where('id_user', $supervisor->id_users)
                            ->with('employee')
                            ->get();
    
        // Fetch all supervisors in the company
        $supervisorsall = User::where(function($query) {
                                $query->where('role', 'supervisor')
                                      ->orWhere('role', 'admin');
                            })
                            ->where('id_company', Auth::user()->id_company)
                            ->with('employee')
                            ->get();
    
        // Return the data as JSON
        return response()->json([
            'positions' => $positions,
            'supervisors' => $supervisors,
            'supervisorsall' => $supervisorsall,
        ]);
    }
    


}
