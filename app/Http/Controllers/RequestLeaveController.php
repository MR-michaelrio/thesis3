<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\RequestLeave;
use Auth;

class RequestLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $leave = RequestLeave::where("id_company",Auth::user()->id_company)->get();
        // dd($leave);
        return view("approval.leave-data",compact("leave"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $leave = Leave::where("id_company",Auth::user()->id_company)->get();
        
        // dd($leave);
        return view("request.leave-request",compact("leave"));
    }

    public function getRemainingQuota(Request $request)
    {
        $leaveTypeId = $request->input('leave_type');  // Get the selected leave type ID

        // Get the leave type from the database based on the selected leave type ID
        $leaveType = Leave::find($leaveTypeId); 
        // Check if leave type exists
        if (!$leaveType) {
            return response()->json(['error' => 'Leave type not found'], 404);
        }

        // Get the default quota for the selected leave type
        $defaultQuota = $leaveType->default_quota;

        // Get the total approved leave for the employee and the selected leave type
        $approvedLeave = RequestLeave::where('id_employee', Auth::user()->employee->id_employee)
                                    ->where('leave_type', $leaveTypeId)
                                    ->where('status', 'approve')
                                    ->sum('requested_quota'); 

        // $approvedLeave = RequestLeave::where('id_employee', Auth::user()->employee->id_employee)->get(); 

        // Calculate the remaining quota
        $remainingQuota = $defaultQuota - $approvedLeave;

        return response()->json([
            'remaining_quota' => max(0, $remainingQuota),  // Ensure remaining quota is not negative
            "id employee" => Auth::user()->employee->id_employee,
            "default qupta"=> $defaultQuota,
            "approvedLeave" => $approvedLeave,
            "leaveTypeId" => $leaveTypeId
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Convert dates from 'DD/MM/YYYY' to 'YYYY-MM-DD'
        $leaveStartDate = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->leave_start_date)->format('Y-m-d H:i');
        $leaveEndDate = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $request->leave_end_date)->format('Y-m-d H:i');

        // Handle file upload if there is a file
        $filePath = null;
        if ($request->hasFile('request_file')) {
            $filePath = $request->file('request_file')->store('leave_requests', 'public'); // Save file
        }
        // Create a new leave request
        $leaveRequest = RequestLeave::create([
            'leave_type' => $request->leave_type,
            'leave_time' => $request->leave_time,
            'leave_start_date' => $leaveStartDate,  // Save formatted date
            'leave_end_date' => $leaveEndDate,      // Save formatted date
            'request_description' => $request->request_description,
            'request_file' => $filePath,            // Save file path
            'id_employee' => Auth::user()->employee->id_employee,            // Employee ID from authenticated user
            'status' => 'Pending',                  // Initial status
            'id_company' => Auth::user()->id_company,
            'requested_quota' => $request->leave_quota_requested    // Use calculated quota
        ]);

        // Redirect back with success message
        return redirect()->route('requestleave.index')->with('success', 'Leave request submitted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_request_leave_hdrs' => 'required|exists:request_leave_hdrs,id_request_leave_hdrs',
            'status' => 'required|in:approve,reject',
        ]);

        // Cari request berdasarkan ID
        $leaveRequest = RequestLeave::findOrFail($request->id_request_leave_hdrs);

        // Update status
        $leaveRequest->status = $request->status;
        $leaveRequest->id_approver = Auth::user()->employee->id_employee;
        $leaveRequest->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Leave request status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
