<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\AssignLeave;
use App\Models\AssignShift;
use App\Models\RequestLeave;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RequestLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (Auth::user()->role == "supervisor") {
            $leave = RequestLeave::where("id_company",Auth::user()->id_company)
                                    ->whereHas('employee.user', function ($query) {
                                        $query->where('id_department', Auth::user()->id_department);
                                    })          
                                    ->get();
        } else if (Auth::user()->role == "employee") {
            $leave = RequestLeave::where("id_company",Auth::user()->id_company)
                                    ->where('id_employee', Auth::user()->employee->id_employee)                              
                                    ->get();
        }else{
            $leave = RequestLeave::where("id_company",Auth::user()->id_company)->with('leaveremaining')->get();
        }
        // dd($leave);
        return view("approval.leave-data",compact("leave"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $leave = AssignLeave::where("id_employee",Auth::user()->employee->id_employee)->get();
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

        $remainingQuota = AssignLeave::where('id_employee', Auth::user()->employee->id_employee)
                                    ->where('id_leave', $leaveTypeId)->first();
        // $approvedLeave = RequestLeave::where('id_employee', Auth::user()->employee->id_employee)->get(); 

        // Calculate the remaining quota

        return response()->json([
            'remaining_quota' => $remainingQuota->remaining,  // Ensure remaining quota is not negative
            "id employee" => Auth::user()->employee->id_employee,
            "default quota"=> $defaultQuota,
            "approvedLeave" => $approvedLeave,
            "leaveTypeId" => $leaveTypeId
        ]);
    }

    public function calculateLeaveQuota(Request $request)
    {
        $employeeId = Auth::user()->employee->id_employee;
        $startDate = Carbon::createFromFormat('d/m/Y H:i', $request->leave_start_date); // Format sesuai input
        $endDate = Carbon::createFromFormat('d/m/Y H:i', $request->leave_end_date);
        
        $leaveTime = $request->leave_time; // Full or Half day
        $totalDays = 0;

        // Loop untuk mengecek setiap hari dalam rentang tanggal
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            // Ambil shift untuk karyawan pada tanggal tersebut
            $shift = DB::table('assign_shift')
                        ->where('id_employee', $employeeId)
                        ->where('day', $date->dayOfWeek + 1)  // Hari ke-1 sampai ke-7, sesuaikan dengan sistem day di database
                        ->first();

            // Jika shift ada, hitung hari
            if ($shift && $shift->id_shift) {
                $totalDays++;
            }
        }

        // Tentukan kuota berdasarkan jenis cuti (Full Day atau Half Day)
        $requestedQuota = ($leaveTime === 'full') ? $totalDays : $totalDays * 0.5;

        return response()->json([
            'requested_quota' => $requestedQuota,
        ]);
    }

    public function store(Request $request)
    {
        $assignleave = AssignLeave::where('id_leave',$request->leave_type)->where('id_employee',Auth::user()->employee->id_employee)->first();
        if ($assignleave && $assignleave->remaining >= $request->leave_quota_requested) {
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

            $assignleave->update([
                "remaining" => $assignleave->remaining - $request->leave_quota_requested
            ]);

            // Redirect back with success message
            return redirect()->route('requestleave.index')->with('success', 'Leave request submitted successfully!');
        } else {
            // Handle case when remaining quota is insufficient
            return redirect()->route('requestleave.index')->with('error', 'Insufficient leave quota available.');
        }    
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

        if($request->status == "reject")
        {
            $assignleave = AssignLeave::where('id_leave', $leaveRequest->leave_type)
                        ->where('id_employee', Auth::user()->employee->id_employee)
                        ->first();
            $newRemaining = $assignleave->remaining + $leaveRequest->requested_quota;

            // Jika newRemaining melebihi quota, batasi menjadi quota
            if ($newRemaining > $assignleave->quota) {
                $newRemaining = $assignleave->quota;
            }

            // Update dengan nilai yang sudah dibatasi
            $assignleave->update([
                "remaining" => $newRemaining
            ]);
        }else{
            $assignleave = AssignLeave::where('id_leave', $leaveRequest->leave_type)
                        ->where('id_employee', Auth::user()->employee->id_employee)
                        ->first();
            $newRemaining = $assignleave->remaining - $leaveRequest->requested_quota;
            // return $newRemaining;
            $assignleave->update([
                "remaining" => $newRemaining
            ]);
        }

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
