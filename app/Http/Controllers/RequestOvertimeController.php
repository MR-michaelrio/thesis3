<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestOvertime;
use App\Models\Attendance;
use App\Models\AttendancePolicy;
use App\Models\AssignShift;

use Auth;  
use Carbon\Carbon;

class RequestOvertimeController extends Controller
{
    public function index()
    {
        // Return all overtime records as a collection of resources
        if (Auth::user()->role == "supervisor") {
            $overtimes = RequestOvertime::where("id_company",Auth::user()->id_company)
                                    ->whereHas('employee.user', function ($query) {
                                        $query->where('id_department', Auth::user()->id_department);
                                    })                                    
                                    ->get();
        } else if (Auth::user()->role == "employee") {
            $overtimes = RequestOvertime::where("id_company",Auth::user()->id_company)
                                    ->where('id_employee', Auth::user()->employee->id_employee)                              
                                    ->get();
        }else{
            $overtimes = RequestOvertime::where("id_company",Auth::user()->id_company)->get();
        }
        return view('approval.overtime-data', compact('overtimes'));;
    }

    public function create()
    {
        return view('request.overtime-request');
    }

    public function store(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y', $request->overtime_date)->format('Y-m-d');

        $attendance = Attendance::where('id_employee', Auth::user()->employee->id_employee)
                                ->where('attendance_date', $date)
                                ->first();
        $filePath = null;
        if ($request->hasFile('request_file')) {
            $filePath = $request->file('request_file')->store('overtime_requests', 'public'); // Save file
        }

        $overtime = RequestOvertime::create([
            "overtime_date" => $date,
            "start" => $request->mulai,
            "end" => $request->akhir,
            "id_employee" => Auth::user()->employee->id_employee,
            "request_description" => $request->request_description,
            "request_file" => $filePath,
            "id_attendance" => $attendance->id_attendance,
            "status" => "pending",
            "id_company" => Auth::user()->id_company
        ]);

        return redirect()->route('overtimes.index');
    }

    public function getOvertimeData($date)
    {
        // Get attendance and overtime data for the selected date
        $attendance = Attendance::where('id_employee', Auth::user()->employee->id_employee)
                                ->where('attendance_date', $date)
                                ->first();

        if ($attendance) {
            // Get overtime policy and shift data
            $attendance_policy = AttendancePolicy::where('id_company', Auth::user()->id_company)->first();
            $overtime_minutes_start = $attendance_policy->overtime_start;
            $overtime_minutes_end = $attendance_policy->overtime_end;

            $dayOfWeek = Carbon::now()->dayOfWeekIso;
            $clock_out = AssignShift::where("id_employee", Auth::user()->employee->id_employee)
                                    ->where('day', $dayOfWeek)
                                    ->first();

            $clock_out_time = Carbon::parse($clock_out->shift->clock_out);

            // Add overtime minutes to the clock-out time to calculate overtime start
            $overtime_start = $clock_out_time->copy()->addMinutes($overtime_minutes_start);

            // Add overtime minutes to the overtime start to calculate overtime end
            $overtime_end = $overtime_start->copy()->addMinutes($overtime_minutes_end);

            // Format the times as 'H:i' before returning
            return response()->json([
                'start' => $overtime_start->format('H:i') . ' - ' . $overtime_end->format('H:i'),
                'mulai' => $overtime_start->format('H:i'),
                'akhir' => $overtime_end->format('H:i'),
                'total_overtime' => $attendance_policy->overtime_start
            ]);

        }
        return response()->json(['error' => 'No attendance data found for selected date',"date"=>$date], 404);
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_overtime' => 'required|exists:request_overtime,id_overtime',
            'status' => 'required|in:approve,reject',
        ]);

        // Cari request berdasarkan ID
        $overtimeRequest = RequestOvertime::findOrFail($request->id_overtime);

        // Update status
        $overtimeRequest->status = $request->status;
        $overtimeRequest->id_approver = Auth::user()->employee->id_employee;
        $overtimeRequest->save();

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Leave request status updated successfully!');
    }
}
