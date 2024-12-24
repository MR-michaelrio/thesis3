<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\FaceEncoding;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\AttendancePolicy;
use App\Models\AssignShift;
use App\Models\RequestOvertime;
use App\Models\User;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('attendance/attendance');
    }

    public function data(Request $request)
    {
        $daterange1 = $request->input('daterange1');
        $daterange2 = $request->input('daterange2');
        
        // Tentukan default tanggal jika tidak ada input
        $startDate = Carbon::now()->startOfMonth()->toDateString();
        $endDate = Carbon::now()->endOfMonth()->toDateString();
        
        // Periksa apakah daterange1 atau daterange2 ada di input
        if ($daterange1) {
            list($startDate, $endDate) = explode(' - ', $daterange1);
            $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->toDateString();
            $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->toDateString();
        } elseif ($daterange2) {
            list($startDate, $endDate) = explode(' - ', $daterange2);
            $startDate = Carbon::createFromFormat('d/m/Y', $startDate)->toDateString();
            $endDate = Carbon::createFromFormat('d/m/Y', $endDate)->toDateString();
        }

        if (Auth::user()->role == "supervisor") {
            $overview = Attendance::with('employee.user', 'shift')
                ->where('id_company', Auth::user()->id_company)
                ->whereHas('employee.user', function($query) {
                    $query->where('id_department', Auth::user()->id_department);
                })
                ->orderBy('attendance_date', 'desc')
                ->get();
            
            $summary = Attendance::with('employee.user', 'shift')
                ->where('id_company', Auth::user()->id_company)
                ->whereHas('employee.user', function($query) {
                    $query->where('id_department', Auth::user()->id_department);
                })
                ->where('attendance_date', Carbon::now()->toDateString())
                ->get();
        } else if (Auth::user()->role == "employee") {
            $overview = Attendance::with('employee.user', 'shift')
                ->where('id_company', Auth::user()->id_company)
                ->where('id_employee', Auth::user()->employee->id_employee)
                ->orderBy('attendance_date', 'desc')
                ->get();    
            return view('attendance/attendance-data',compact("overview"));
        }else{
            $overview = Attendance::with('employee.user', 'shift')
                            ->where('id_company', Auth::user()->id_company)
                            ->when($daterange1, function ($query) use ($startDate, $endDate) {
                                return $query->whereBetween('attendance_date', [$startDate, $endDate]);
                            })
                            ->orderBy('attendance_date', 'desc')  // Sort by 'attendance_date' in ascending order
                            ->get();
            $summary = DB::table('attendance')
                            ->join('employee', 'attendance.id_employee', '=', 'employee.id_employee')  // Join the employee table
                            ->join('users', 'employee.id_users', '=', 'users.id_user')  // Join the user table via employee
                            ->join('department', 'users.id_department', '=', 'department.id_department')
                            ->select(
                                'attendance.id_employee',
                                'employee.full_name',
                                'users.identification_number',  // Access the identification_number from the user table
                                'department.department_code',  // Access the department_code from the user table
                                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(daily_total, "%H:%i")))) as total_daily_total'),
                                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(IFNULL(total_overtime, "00:00"), "%H:%i")))) as total_overtime'),
                            )
                            ->where('attendance.id_company', Auth::user()->id_company)
                            ->whereBetween('attendance.attendance_date', [$startDate, $endDate])
                            ->groupBy('attendance.id_employee')
                            ->get();
        }

        
        return view('attendance/attendance-data',compact("overview","summary"));
    }

    public function data2()
    {
        //
        return "seru";
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id){

    }

    public function create()
    {
        //
        $employee = Employee::where("id_company",Auth::user()->id_company)->get();
        $facelist = FaceEncoding::where("id_company",Auth::user()->id_company)->get();
        return view('settings/facerecognition-add',compact("employee","facelist"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $client = new Client();

        try {
            $imagePath = $request->file('image')->getPathname();
            $imageName = $request->file('image')->getClientOriginalName();

            $response = $client->post('http://localhost:6002/train_face', [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $imageName,
                    ],
                    [
                        'name' => 'id_employee',
                        'contents' => $request->input('id_employee'),
                    ]
                    ,
                    [
                        'name' => 'id_company',
                        'contents' => Auth::user()->id_company,
                    ],
                ],
            ]);

            $body = json_decode($response->getBody(), true);

            // Debug: log the response from Flask
            \Log::info('Flask response: ' . print_r($body, true));

            if (isset($body['message'])) {
                return redirect()->back()->with('success', $body['message']);
            } else if (isset($body['error'])) {
                return redirect()->back()->with('error', $body['error']);
            } else {
                return redirect()->back()->with('error', 'An unexpected error occurred.');
            }
        } catch (\Exception $e) {
            \Log::error('Error in train method: ' . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function recognize(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'No image uploaded'], 400);
        }
    
        try {
            $client = new Client();
            $response = $client->post('http://185.199.53.230:6002/process_frame', [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => fopen($request->file('image')->getPathname(), 'r'),
                        'filename' => 'frame.jpg',
                    ],
                ],
            ]);
    
            return $response->getBody();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkin(Request $request)
    {
        $currentTime = Carbon::now('Asia/Jakarta'); // Get the current time using Carbon
        $dayOfWeek = $currentTime->dayOfWeekIso;
        // Get attendance date and clock-in time from the request or default to the current date/time
        $attendance_date = $request->attendance_date; // Format YYYY-MM-DD
        $attendance_clock = $request->clock; // Default to current time if not provided
        $id_employee = $request->id_employee;
        // Fetch the employee's shift assignment
        $assignshift = AssignShift::where('id_employee', $id_employee)->where('day', $dayOfWeek)->first();

        // If no shift assignment found, return an error response
        if (!$assignshift->id_shift) {
            return response()->json([
                'message' => 'No shift assignment found for the employee',
            ], 201); // Not Found
        }

        $attendance = Attendance::where('id_employee', $id_employee)
                            ->where('attendance_date', $attendance_date)
                            ->first();

        // If attendance exists and clock-out is already recorded, return message
        if ($attendance && $attendance->clock_out) {
            return response()->json([
                'message' => 'Already clocked out!',
            ], 201);
        }
        $employee = Employee::where("id_employee",$request->id_employee)->with('user')->first();

        // Check if the current time is after the employee's clock-out time
        if ($currentTime->format('H:i:s') >= $assignshift->shift->clock_out) {
            if ($attendance) {
                $clockIn = Carbon::createFromFormat('H:i:s', $attendance->clock_in);
                $clockOut = Carbon::createFromFormat('H:i:s', $attendance_clock);
            
                // Check for a related RequestOvertime
                $requestOvertime = RequestOvertime::where('id_employee', $attendance->id_employee)
                                                  ->where('overtime_date', $attendance->attendance_date)
                                                  ->first();
            
                // Handle clock-out based on RequestOvertime
                if ($requestOvertime) {
                    $overtimeEnd = Carbon::createFromFormat('H:i:s', $requestOvertime->end);
                    $overtimeStart = Carbon::createFromFormat('H:i:s', $requestOvertime->start);
            
                    if ($clockOut->greaterThan($overtimeEnd)) {
                        $clockOut = $overtimeEnd;
                    }
            
                    // Calculate overtime if clock-out is after the start of overtime
                    if ($clockOut->greaterThanOrEqualTo($overtimeStart)) {
                        $overtimeMinutes = $overtimeStart->diffInMinutes($clockOut);
                        
                        $overtimeHours = floor($overtimeMinutes / 60);
                        $overtimeRemainingMinutes = $overtimeMinutes % 60;

                        $attendance->total_overtime = sprintf('%02d:%02d', $overtimeHours, $overtimeRemainingMinutes);
                        $attendance->attendance_status = 'overtime';

                    } else {
                        $attendance->total_overtime = null; // No overtime if clock-out is before overtime start
                        $attendance->attendance_status = 'present';
                    }
                } else {
                    $attendance->total_overtime = null; // No RequestOvertime means no overtime
                    $attendance->attendance_status = 'present';
                }
            
                // Update clock-out and calculate total hours worked
                $attendance->clock_out = $clockOut->format('H:i:s');
                $dailyTotal = $clockIn->diff($clockOut);
                $attendance->daily_total = sprintf('%02d:%02d', $dailyTotal->h, $dailyTotal->i);
            
                // Update attendance status and save
                $attendance->save();
            
                // Return a response indicating success
                return response()->json([
                    'message' => 'Attendance clock-out updated!',
                    'attendance' => $attendance,
                    'employee_name' => $employee->full_name,
                    'employee_id' =>$employee->user->identification_number,
                    'time' => "Clock Out"
                ], 200); // OK
            }else {
                // If no attendance record is found to update
                return response()->json([
                    'message' => 'No attendance record found to update.',
                ], 201); // Not Found
            }
        } else {
            // If attendance exists and clock-in is already recorded, return message
            // if ($attendance && $attendance->clock_in) {
            //     return response()->json([
            //         'message' => 'Already clocked in!',
            //     ], 201);
            // }
            // Get the clock-in time assigned to the shift
            $clock_in_assign = $assignshift->shift->clock_in;
            $attendance_policy = AttendancePolicy::where("id_company",Auth::user()->id_company)->first();
            $late_tolerance = $attendance_policy->late_tolerance;

            $clock_in_assign_minutes = (int)date('H', strtotime($clock_in_assign)) * 60 + (int)date('i', strtotime($clock_in_assign));
            $allowed_latest_time = $clock_in_assign_minutes + $late_tolerance;

            $attendance_clock_minutes = (int)date('H', strtotime($attendance_clock)) * 60 + (int)date('i', strtotime($attendance_clock));

            $attendance_status = ($attendance_clock_minutes > $allowed_latest_time) ? 'late' : 'present';

            
            // Determine the attendance status based on whether the employee is late or on time
            // $attendance_status = (($clock_in_assign + $late_tolerance) < $attendance_clock) ? 'late' : 'present';

            // Create a new attendance record for the employee
            $attendance = Attendance::create([
                'id_employee' => $request->id_employee, // Ensure the employee ID is passed correctly
                'attendance_date' => $attendance_date,
                'shift_id' => $assignshift->id_shift,
                'clock_in' => $attendance_clock,
                'attendance_status' => $attendance_status,
                'id_company' => Auth::user()->id_company,
            ]);

            // Return a success response after storing the attendance
            return response()->json([
                'message' => 'Attendance successfully stored!',
                'attendance' => $attendance,
                'employee_name' => $employee->full_name,
                'employee_id' =>$employee->user->identification_number,
                'time' => "Clock In"
            ], 201); // Created
        }
    }

    public function getAttendanceData()
    {
        $idCompany = Auth::user()->id_company;

        if(Auth::user()->role == "admin")
        {
            $attendanceData = DB::table('attendance')
                ->select(
                    DB::raw("MONTH(attendance_date) as month"),
                    DB::raw("SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present"),
                    DB::raw("SUM(CASE WHEN attendance_status = 'late' THEN 1 ELSE 0 END) as late"),
                    DB::raw("SUM(CASE WHEN attendance_status = 'on_leave' THEN 1 ELSE 0 END) as on_leave")
                )
                ->where('id_company', $idCompany)
                ->groupBy(DB::raw("MONTH(attendance_date)"))
                ->get();
        
        }else{
            $attendanceData = DB::table('attendance')
                ->select(
                    DB::raw("MONTH(attendance_date) as month"),
                    DB::raw("SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present"),
                    DB::raw("SUM(CASE WHEN attendance_status = 'late' THEN 1 ELSE 0 END) as late"),
                    DB::raw("SUM(CASE WHEN attendance_status = 'on_leave' THEN 1 ELSE 0 END) as on_leave")
                )
                ->where('id_company', $idCompany)
                ->where('id_employee', Auth::user()->employee->id_employee)
                ->groupBy(DB::raw("MONTH(attendance_date)"))
                ->get();
        }
        
        return response()->json($attendanceData);
    }

    public function manualattendance(Request $request)
    {
        $currentTime = Carbon::now('Asia/Jakarta'); // Get the current time using Carbon
        $dayOfWeek = $currentTime->dayOfWeekIso;
        // Get attendance date and clock-in time from the request or default to the current date/time
        $attendance_date = $currentTime->format("Y-m-d"); // Format YYYY-MM-DD
        $attendance_clock = $currentTime->format('H:i:s'); // Default to current time if not provided

        $id_employe = User::where('identification_number',$request->id_identification)->with('employee')->first();
        $id_employee = $id_employe->employee->id_employee;
        // Fetch the employee's shift assignment
        $assignshift = AssignShift::where('id_employee', $id_employee)->where('day', $dayOfWeek)->first();

        // If no shift assignment found, return an error response
        if (!$assignshift->id_shift) {
            return response()->json([
                'message' => 'No shift assignment found for the employee.',
            ], 201);
        }

        $attendance = Attendance::where('id_employee', $id_employee)
                            ->where('attendance_date', $attendance_date)
                            ->first();

        // If attendance exists and clock-out is already recorded, return message
        if ($attendance && $attendance->clock_out) {
            return response()->json([
                'message' => 'Already clocked out!',
            ], 201);
        }

        // Check if the current time is after the employee's clock-out time
        if ($currentTime->format('H:i:s') >= $assignshift->shift->clock_out) {
            if ($attendance) {
                $clockIn = Carbon::createFromFormat('H:i:s', $attendance->clock_in);
                $clockOut = Carbon::createFromFormat('H:i:s', $attendance_clock);
            
                // Check for a related RequestOvertime
                $requestOvertime = RequestOvertime::where('id_employee', $attendance->id_employee)
                                                  ->where('overtime_date', $attendance->attendance_date)
                                                  ->first();
            
                // Handle clock-out based on RequestOvertime
                if ($requestOvertime) {
                    $overtimeEnd = Carbon::createFromFormat('H:i:s', $requestOvertime->end);
                    $overtimeStart = Carbon::createFromFormat('H:i:s', $requestOvertime->start);
            
                    if ($clockOut->greaterThan($overtimeEnd)) {
                        $clockOut = $overtimeEnd;
                    }
            
                    // Calculate overtime if clock-out is after the start of overtime
                    if ($clockOut->greaterThanOrEqualTo($overtimeStart)) {
                        $overtimeMinutes = $overtimeStart->diffInMinutes($clockOut);
                        
                        $overtimeHours = floor($overtimeMinutes / 60);
                        $overtimeRemainingMinutes = $overtimeMinutes % 60;

                        $attendance->total_overtime = sprintf('%02d:%02d', $overtimeHours, $overtimeRemainingMinutes);
                        $attendance->attendance_status = 'overtime';

                    } else {
                        $attendance->total_overtime = null; // No overtime if clock-out is before overtime start
                        $attendance->attendance_status = 'present';
                    }
                } else {
                    $attendance->total_overtime = null; // No RequestOvertime means no overtime
                    $attendance->attendance_status = 'present';
                }
            
                // Update clock-out and calculate total hours worked
                $attendance->clock_out = $clockOut->format('H:i:s');
                $dailyTotal = $clockIn->diff($clockOut);
                $attendance->daily_total = sprintf('%02d:%02d', $dailyTotal->h, $dailyTotal->i);
            
                // Update attendance status and save
                $attendance->save();
            
                // Return a response indicating success
                return response()->json([
                    'message' => 'Attendance clock-out updated!',
                    'attendance' => $attendance,
                    'employee_name' => $id_employe->employee->full_name,
                    'employee_id' =>$id_employe->identification_number,
                    'time' => "Clock Out"
                ], 201); // OK
            }else {
                // If no attendance record is found to update
                return response()->json([
                    'message' => 'No attendance record found.',
                ], 201); // Not Found
            }
        } else {
            // If attendance exists and clock-in is already recorded, return message
            // if ($attendance && $attendance->clock_in) {
            //     return response()->json([
            //         'message' => 'Already clocked in!',
            //     ], 201);
            // }
            // Get the clock-in time assigned to the shift
            $clock_in_assign = $assignshift->shift->clock_in;
            $attendance_policy = AttendancePolicy::where("id_company",Auth::user()->id_company)->first();
            $late_tolerance = $attendance_policy->late_tolerance;

            $clock_in_assign_minutes = (int)date('H', strtotime($clock_in_assign)) * 60 + (int)date('i', strtotime($clock_in_assign));
            $allowed_latest_time = $clock_in_assign_minutes + $late_tolerance;

            $attendance_clock_minutes = (int)date('H', strtotime($attendance_clock)) * 60 + (int)date('i', strtotime($attendance_clock));

            $attendance_status = ($attendance_clock_minutes > $allowed_latest_time) ? 'late' : 'present';

            
            // Determine the attendance status based on whether the employee is late or on time
            // $attendance_status = (($clock_in_assign + $late_tolerance) < $attendance_clock) ? 'late' : 'present';

            // Create a new attendance record for the employee
            $attendance = Attendance::create([
                'id_employee' => $id_employee, // Ensure the employee ID is passed correctly
                'attendance_date' => $attendance_date,
                'shift_id' => $assignshift->id_shift,
                'clock_in' => $attendance_clock,
                'attendance_status' => $attendance_status,
                'id_company' => Auth::user()->id_company,
            ]);

            // Return a success response after storing the attendance
            return response()->json([
                'message' => 'Attendance successfully stored!',
                'attendance' => $attendance,
                'employee_name' => $id_employe->employee->full_name,
                'employee_id' =>$id_employe->identification_number,
                'time' => "Clock In"
                
            ], 201); // Created
        }
    }

    public function destroy($id){
        $facelist = FaceEncoding::findOrFail($id);
        $facelist->delete();

        return redirect()->back()->with('success', 'Face deleted successfully');
    }
}
