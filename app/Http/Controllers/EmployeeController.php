<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\AddressEmployee;
use App\Models\User;
use App\Models\Department;
use App\Models\DepartmentPosition;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user.department', 'user.position'])->where("id_company",Auth::user()->id_company)->get();
        return view('employee.employee-data', compact('employees'));
    }



    public function create()
    {
        $department = Department::where("id_company",Auth::user()->id_company)->get();
        $departmentPosition = DepartmentPosition::where("id_company",Auth::user()->id_company)->get();
        $user = User::where("role", "supervisor")->where("id_company",Auth::user()->id_company)->get();
        return view('employee.employee-add', compact('department', 'departmentPosition', 'user'));
    }

    public function store(Request $request)
    {
        // Validasi input
        // $request = $request->validate([
        //     // Validasi employee_address
        //     'country' => 'required|string|max:255',
        //     'postal_code' => 'required|string|max:20',
        //     'full_address' => 'required|string|max:255',

        //     // Validasi employee
        //     'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        //     'first_name' => 'required|string|max:50',
        //     'last_name' => 'required|string|max:50',
        //     'gender' => 'required|string|in:male,female',
        //     'marital' => 'required|string|in:single,married',
        //     'religion' => 'required|string|max:50',
        //     'place_of_birth' => 'required|string|max:100',
        //     'date_of_birth' => 'required|date',
        //     'id_company_employee' => 'required|integer|exists:companies,id',

        //     // Validasi user
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|string|email|max:255|unique:users,email',
        //     'password' => 'required|string|min:8',
        //     'id_department' => 'required|integer|exists:departments,id',
        //     'id_department_position' => 'required|integer|exists:department_positions,id',
        //     'supervisor' => 'nullable|integer|exists:users,id',
        //     'start_work' => 'required|date',
        //     'stop_work' => 'nullable|date|after_or_equal:start_work',
        //     'role' => 'required|string|max:50',
        //     'phone' => 'required|string|max:15',
        //     'emergency_name' => 'required|string|max:100',
        //     'emergency_relation' => 'required|string|max:50',
        //     'emergency_phone' => 'required|string|max:15',
        // ]);

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
                'role' => $request['role'],
                'phone' => $request['phone'],
                'emergency_name' => $request['emergency_name'],
                'emergency_relation' => $request['emergency_relation'],
                'emergency_phone' => $request['emergency_phone'],
                'id_company' => Auth::user()->id_company,
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

            // Redirect ke route employee.index dengan pesan sukses
            return redirect()->route('employee.index')->with('success', 'Employee created successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
        
    }


    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $addresses = AddressEmployee::all();
        $users = User::all();
        return view('employee.edit', compact('employee', 'addresses', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'nullable|string',
            'marital' => 'nullable|string',
            'religion' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'id_address_employee' => 'nullable|integer',
            'id_users' => 'nullable|integer',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return redirect()->route('employee.index')->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return redirect()->route('employee.index')->with('success', 'Employee deleted successfully!');
    }
}
