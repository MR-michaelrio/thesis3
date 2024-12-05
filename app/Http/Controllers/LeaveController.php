<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::all();
        return view('settings.leave-data', compact('leaves'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'leave_name' => 'required|string|max:255',
        //     'category' => 'required|string|max:255',
        //     'allocation' => 'required|integer',
        //     'valid_date_from' => 'required|date',
        //     'valid_date_end' => 'required|date|after_or_equal:valid_date_from',
        //     'default_quota' => 'required|integer',
        //     'description' => 'nullable|string',
        //     'id_company' => 'required|integer|exists:company,id_company',
        // ]);
        
        $user = User::where("id_user", Auth::id())->first();
        $data = array_merge($request->all(), ['id_company' => $user->id_company]);
        Leave::create($data);


        return redirect()->route('leaves.index')->with('success', 'Leave created successfully.');
    }

    public function edit($id)
    {
        $leave = Leave::findOrFail($id);
        return view('leaves.edit', compact('leave'));
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'leave_name' => 'required|string|max:255',
        //     'category' => 'required|string|max:255',
        //     'allocation' => 'required|integer',
        //     'valid_date_from' => 'required|date',
        //     'valid_date_end' => 'required|date|after_or_equal:valid_date_from',
        //     'default_quota' => 'required|integer',
        //     'description' => 'nullable|string',
        //     'id_company' => 'required|integer|exists:company,id_company',
        // ]);

        $leave = Leave::findOrFail($id);
        $data = $request->all();
        $data['valid_date_from'] = $request->input('valid_date_from') ?: null;
        $data['valid_date_end'] = $request->input('valid_date_end') ?: null;

        $leave->update($data);

        return redirect()->route('leaves.index')->with('success', 'Leave updated successfully.');
    }

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return redirect()->route('leaves.index')->with('success', 'Leave deleted successfully.');
    }
}
