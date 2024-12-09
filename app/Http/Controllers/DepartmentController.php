<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\DepartmentPosition;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\DB;

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
        $supervisors = DB::select("SELECT * FROM employee e JOIN users u ON e.id_users = u.id_user WHERE u.role = 'supervisor'");        
        
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
        $position = DepartmentPosition::where("id_department", $department->id_department)->get();

        $supervisors = Employee::whereHas('user', function($query) {
            $query->where('role', 'supervisor');
        })->get();
        
        return view('settings.department-edit', compact('department','supervisors','departments',"position"));
    }

    // Update a department and its positions
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $department->update([
            'department_name' => $request->department_name,
            'department_code' => $request->department_code,
            'id_supervisor' => $request->id_supervisor,
            'description' => $request->description,
            'id_company' => 1, // Example company ID
        ]);

        // Ambil posisi yang ada untuk departemen ini
        $existingPositions = DepartmentPosition::where('id_department', $department->id)
        ->pluck('position_title', 'id_department_position') // Gunakan 'id_department_position'
        ->toArray();
        $positions = json_decode($request->positions, true);

        if($positions){
        // Update atau tambahkan posisi baru
            foreach ($positions as $position) {
                if (!in_array($position['title'], $existingPositions)) {
                    // Jika posisi tidak ada, buat posisi baru
                    DepartmentPosition::create([
                        'position_title' => $position['title'],
                        'position_description' => $position['description'],
                        'id_department' => $id,
                        'id_company' => Auth::user()->id_company, // Example company ID
                    ]);
                }
            }
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

    public function updateposition(Request $request)
    {

        // Find the position by ID and update it
        $position = DepartmentPosition::findOrFail($request->id);
        $position->position_title = $request->title;
        $position->position_description = $request->description;
        $position->save();

        return response()->json(['message' => 'Position updated successfully', 'position' => $position,"success"=>200]);
    }

}
