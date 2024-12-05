<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentPosition;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Auth;

class DepartmentController extends Controller
{
    public function index()
    {
        // Fetch all departments with their associated positions
        $departments = Department::with(['positions', 'supervisor', 'parent'])->get();
        return view('settings.department-data', compact('departments'));
    }

    public function create()
    {
        $departments = Department::all();
        // Assuming Employee has a relationship with User
        $supervisors = Employee::whereHas('user', function($query) {
            $query->where('role', 'supervisor');
        })->get();
        // dd($supervisors);
        
        return view('settings.department-add', compact('departments', 'supervisors'));
    }

    public function store(Request $request)
    {
        // Create a new department
        $department = Department::create([
            'department_name' => $request->department_name,
            'department_code' => $request->department_code,
            'id_supervisor' => $request->id_supervisor,
            'id_parent' => $request->id_parent,
            'description' => $request->description,
            'id_company' => auth()->user()->id_company,  // Assuming company ID is tied to authenticated user
        ]);
    
        // Decode the positions JSON and store them
        $positions = json_decode($request->positions, true);
        foreach ($positions as $positionData) {
            $department->positions()->create([
                'position_title' => $positionData['title'],
                'position_description' => $positionData['description'],
                'id_department' => $department->id_department, // Ensure this is populated
                'id_company' => auth()->user()->id_company,    // Ensure this is populated
            ]);
        }
    
        return redirect()->route('department.index')->with('success', 'Department and positions created successfully!');
    }
    

    
    // Show the form to edit a department and its positions
    public function edit($id)
    {
        $department = Department::with('positions')->findOrFail($id);
        $departments = Department::all();

        $supervisors = Employee::whereHas('user', function($query) {
            $query->where('role', 'supervisor');
        })->get();
        return view('settings.department-edit', compact('department','supervisors','departments'));
    }

    // Update a department and its positions
    public function update(Request $request, $id)
    {
        $request->validate([
            'department_name' => 'required',
            'department_code' => 'required',
            'id_supervisor' => 'required|integer',
            'description' => 'nullable',
            'positions' => 'array|required',
        ]);

        $department = Department::findOrFail($id);
        $department->update([
            'department_name' => $request->department_name,
            'department_code' => $request->department_code,
            'id_supervisor' => $request->id_supervisor,
            'description' => $request->description,
            'id_company' => 1, // Example company ID
        ]);

        // Update department positions
        foreach ($request->positions as $position) {
            DepartmentPosition::updateOrCreate(
                ['id' => $position['id']], // Update by ID
                [
                    'position_title' => $position['title'],
                    'position_description' => $position['description'],
                    'id_department' => $department->id,
                    'id_company' => 1, // Example company ID
                ]
            );
        }

        return redirect()->route('department.index')->with('success', 'Department updated successfully');
    }

    // Delete department and its positions
    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->positions()->delete();
        $department->delete();

        return redirect()->route('department.index')->with('success', 'Department deleted successfully');
    }
}
