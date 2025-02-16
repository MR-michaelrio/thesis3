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
use App\Models\RequestLeave;

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
            $datenow = Carbon::now()->toDateString();
                $overview = Attendance::with('employee.user', 'shift')
                ->where('id_company', Auth::user()->id_company)
                ->whereHas('employee.user', function($query) {
                    $query->where('id_department', Auth::user()->id_department);
                })
                ->orderBy('attendance_date', 'desc')
                ->get();

                $summary = DB::table('attendance')
                ->join('employee', 'attendance.id_employee', '=', 'employee.id_employee')  // Join the employee table
                ->join('users', 'employee.id_users', '=', 'users.id_user')                 // Join the user table via employee
                ->join('department', 'users.id_department', '=', 'department.id_department') // Join the department table
                ->leftJoin(DB::raw('(SELECT id_employee, SUM(requested_quota) as total_quota 
                            FROM request_leave_hdrs 
                            WHERE status = "approve" 
                            GROUP BY id_employee) as leaves'), 
                        'employee.id_employee', '=', 'leaves.id_employee')       
                ->leftJoin(DB::raw('(SELECT 
                            s.id_employee, 
                            COUNT(*) AS total_absent
                        FROM (
                            SELECT DATE_ADD("' . $datenow .'", INTERVAL t4.i * 10 + t3.i * 1 DAY) AS date
                            FROM (SELECT 0 i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t4,
                                (SELECT 0 i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t3
                            WHERE DATE_ADD("' . $datenow .'", INTERVAL t4.i * 10 + t3.i * 1 DAY) <= "' . $datenow .'"
                        ) d
                        JOIN assign_shift AS s ON (CASE WHEN DAYOFWEEK(d.date) = 1 THEN 7 ELSE DAYOFWEEK(d.date) - 1 END) = s.day
                        LEFT JOIN attendance AS a ON a.id_employee = s.id_employee AND a.attendance_date = d.date
                        WHERE s.id_shift IS NOT NULL AND a.attendance_date IS NULL
                        GROUP BY s.id_employee) AS absents'), 
                    'attendance.id_employee', '=', 'absents.id_employee')
                ->select(
                    'attendance.id_employee',
                    'employee.full_name',
                    'users.identification_number',               // Access the identification_number from the user table
                    'department.department_code',                // Access the department_code from the department table
                    DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(daily_total, "%H:%i:%s")))) as total_daily_total'),
                    DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(IFNULL(total_overtime, "00:00"), "%H:%i:%s")))) as total_overtime'),
                    DB::raw('IFNULL(leaves.total_quota, 0) as total_approved_leave_quota'),
                    DB::raw('IFNULL(absents.total_absent, 0) as total_absent')

                    )
                    
                ->where('attendance.id_company', Auth::user()->id_company)
                ->where('users.id_department', Auth::user()->id_department)
                ->where('attendance_date', $datenow)
                ->groupBy(
                    'attendance.id_employee',
                    'employee.full_name',
                    'users.identification_number',
                    'department.department_code',
                    'leaves.total_quota',
                    'absents.total_absent'
                )
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
                            ->join('users', 'employee.id_users', '=', 'users.id_user')                 // Join the user table via employee
                            ->join('department', 'users.id_department', '=', 'department.id_department') // Join the department table
                            ->leftJoin(DB::raw('(SELECT id_employee, SUM(requested_quota) as total_quota 
                                        FROM request_leave_hdrs 
                                        WHERE status = "approve" 
                                        GROUP BY id_employee) as leaves'), 
                            'employee.id_employee', '=', 'leaves.id_employee')       
                            ->leftJoin(DB::raw('(SELECT 
                            s.id_employee, 
                            COUNT(*) AS total_absent
                          FROM (
                            SELECT DATE_ADD("' . $startDate .'", INTERVAL t4.i * 10 + t3.i * 1 DAY) AS date
                            FROM (SELECT 0 i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                                  UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t4,
                                 (SELECT 0 i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 
                                  UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) t3
                            WHERE DATE_ADD("' . $startDate .'", INTERVAL t4.i * 10 + t3.i * 1 DAY) <= "' . $endDate .'"
                          ) d
                          JOIN assign_shift AS s ON (CASE WHEN DAYOFWEEK(d.date) = 1 THEN 7 ELSE DAYOFWEEK(d.date) - 1 END) = s.day
                          LEFT JOIN attendance AS a ON a.id_employee = s.id_employee AND a.attendance_date = d.date
                          WHERE s.id_shift IS NOT NULL AND a.attendance_date IS NULL
                          GROUP BY s.id_employee) AS absents'), 
                    'attendance.id_employee', '=', 'absents.id_employee')

                            ->select(
                                'attendance.id_employee',
                                'employee.full_name',
                                'users.identification_number',               // Access the identification_number from the user table
                                'department.department_code',                // Access the department_code from the department table
                                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(daily_total, "%H:%i:%s")))) as total_daily_total'),
                                DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(IFNULL(total_overtime, "00:00"), "%H:%i:%s")))) as total_overtime'),
                                DB::raw('IFNULL(leaves.total_quota, 0) as total_approved_leave_quota'),
                                DB::raw('IFNULL(absents.total_absent, 0) as total_absent')

                                )
                                
                            ->where('attendance.id_company', Auth::user()->id_company)
                            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                                return $query->whereBetween('attendance.attendance_date', [$startDate, $endDate]);
                            }, function ($query) {
                                // Jika $startDate dan $endDate tidak ada, gunakan tanggal hari ini sebagai default
                                $today = date('Y-m-d');
                                
                                return $query->whereDate('attendance.attendance_date', $today);
                            })
                            ->groupBy(
                                'attendance.id_employee',
                                'employee.full_name',
                                'users.identification_number',
                                'department.department_code',
                                'leaves.total_quota',
                                'absents.total_absent'
                            )
                            ->get();                        
        }

        
        return view('attendance/attendance-data',compact("overview","summary"));
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

            $response = $client->post('http://185.199.53.230:6002/train_face', [
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
                    [
                        'name' => 'id_company', // Tambahkan 'name' untuk id_company
                        'contents' => Auth::user()->id_company
                    ]
                ],
            ]);
    
            return $response->getBody();
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkin1(Request $request)
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
            if ($attendance && $attendance->clock_in) {
                return response()->json([
                    'message' => 'Already clocked in!',
                ], 201);
            }
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

    public function checkin(Request $request)
{
    $currentTime = Carbon::now('Asia/Jakarta'); // Waktu saat ini
    $dayOfWeek = $currentTime->dayOfWeekIso;

    // Data dari request
    $attendance_date = $request->attendance_date; // Format YYYY-MM-DD
    $attendance_clock = $request->clock; // Jam absen
    $id_employee = $request->id_employee;

    // Ambil shift karyawan
    $assignshift = AssignShift::where('id_employee', $id_employee)->where('day', $dayOfWeek)->first();

    if (!$assignshift) {
        return response()->json(['message' => 'No shift assignment found for the employee'], 201);
    }

    $shiftStart = Carbon::parse($assignshift->shift->clock_in, 'Asia/Jakarta');
    $shiftEnd = Carbon::parse($assignshift->shift->clock_out, 'Asia/Jakarta');

    // **Cek jika shift melewati tengah malam**
    if ($shiftEnd->lt($shiftStart)) {
        $shiftEnd->addDay();
    }

    $attendance = Attendance::where('id_employee', $id_employee)
                            ->where('attendance_date', $attendance_date)
                            ->first();

    // **Jika sudah clock-out, tidak bisa absen lagi**
    if ($attendance && $attendance->clock_out) {
        return response()->json(['message' => 'Already clocked out!'], 201);
    }

    $employee = Employee::where("id_employee", $id_employee)->with('user')->first();

    // **Cek apakah masih dalam rentang shift**
    if ($attendance) {
        $clockIn = Carbon::createFromFormat('H:i:s', $attendance->clock_in);
        $clockOut = Carbon::createFromFormat('H:i:s', $attendance_clock);

        if ($currentTime->lt($shiftEnd)) {
            return response()->json(['message' => 'Cannot clock-out before the shift ends.'], 201);
        }
        
        $requestOvertime = RequestOvertime::where('id_employee', $attendance->id_employee)
                                          ->where('overtime_date', $attendance->attendance_date)
                                          ->first();

        if ($requestOvertime) {
            $overtimeStart = Carbon::createFromFormat('H:i:s', $requestOvertime->start);
            $overtimeEnd = Carbon::createFromFormat('H:i:s', $requestOvertime->end);

            if ($clockOut->greaterThan($overtimeEnd)) {
                $clockOut = $overtimeEnd;
            }

            if ($clockOut->greaterThanOrEqualTo($overtimeStart)) {
                $overtimeMinutes = $overtimeStart->diffInMinutes($clockOut);
                $overtimeHours = floor($overtimeMinutes / 60);
                $overtimeRemainingMinutes = $overtimeMinutes % 60;
                
                $attendance->total_overtime = sprintf('%02d:%02d', $overtimeHours, $overtimeRemainingMinutes);
                $attendance->attendance_status = 'overtime';
            }
        } else {
            $attendance->total_overtime = null;
            $attendance->attendance_status = 'present';
        }

        $attendance->clock_out = $clockOut->format('H:i:s');
        $dailyTotal = $clockIn->diff($clockOut);
        $attendance->daily_total = sprintf('%02d:%02d', $dailyTotal->h, $dailyTotal->i);
        $attendance->save();

        return response()->json([
            'message' => 'Attendance clock-out updated!',
            'attendance' => $attendance,
            'employee_name' => $employee->full_name,
            'employee_id' => $employee->user->identification_number,
            'time' => "Clock Out"
        ], 200);
    }
    if ($currentTime <= $shiftEnd) {
        $clock_in_assign = $assignshift->shift->clock_in;
        $attendance_policy = AttendancePolicy::where("id_company", Auth::user()->id_company)->first();
        $late_tolerance = $attendance_policy->late_tolerance;

        $clock_in_assign_minutes = (int)date('H', strtotime($clock_in_assign)) * 60 + (int)date('i', strtotime($clock_in_assign));
        $allowed_latest_time = $clock_in_assign_minutes + $late_tolerance;
        $attendance_clock_minutes = (int)date('H', strtotime($attendance_clock)) * 60 + (int)date('i', strtotime($attendance_clock));

        $attendance_status = ($attendance_clock_minutes > $allowed_latest_time) ? 'late' : 'present';

        $attendance = Attendance::create([
            'id_employee' => $request->id_employee,
            'attendance_date' => $attendance_date,
            'shift_id' => $assignshift->id_shift,
            'clock_in' => $attendance_clock,
            'attendance_status' => $attendance_status,
            'id_company' => Auth::user()->id_company,
        ]);

        return response()->json([
            'message' => 'Attendance successfully stored!',
            'attendance' => $attendance,
            'employee_name' => $employee->full_name,
            'employee_id' => $employee->user->identification_number,
            'time' => "Clock In"
        ], 201);
    } else {
        return response()->json(['message' => 'Clock-in cannot be done after shift ends.'], 201);
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

        $id_employe = User::where('identification_number',$request->id_identification)->where("id_company",Auth::user()->id_company)->with('employee')->first();
        \Log::info('Employee ID not found for input: ' . $id_employe);

        if (!$id_employe || !$id_employe->employee) {
            \Log::info('Employee ID not found for input: ' . $request->id_identification);

            return response()->json([
                'message' => 'Employee with this ID not found.',
            ], 200);
        }
        
        $id_employee = $id_employe->employee->id_employee;
        // Fetch the employee's shift assignment
        $assignshift = AssignShift::where('id_employee', $id_employee)->where('day', $dayOfWeek)->first();
        \Log::info('Employee ID not found for input: ' . $assignshift);

        // If no shift assignment found, return an error response
        if (!$assignshift->id_shift) {
            return response()->json([
                'message' => 'No shift assignment found for the employee.',
            ], 200);
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
                ], 200); // OK
            }else {
                // If no attendance record is found to update
                return response()->json([
                    'message' => 'No attendance record found.',
                ], 200); // Not Found
            }
        } else {
            // If attendance exists and clock-in is already recorded, return message
            if ($attendance && $attendance->clock_in) {
                return response()->json([
                    'message' => 'Already clocked in!',
                ], 201);
            }
            // Get the clock-in time assigned to the shift
            $clock_in_assign = $assignshift->shift->clock_in;
            $attendance_policy = AttendancePolicy::where("id_company",Auth::user()->id_company)->first();
            \Log::info('late_tolerance not found for input: ' . $attendance_policy);
            $late_tolerance = $attendance_policy->late_tolerance;

            $clock_in_assign_minutes = (int)date('H', strtotime($clock_in_assign)) * 60 + (int)date('i', strtotime($clock_in_assign));
            $allowed_latest_time = $clock_in_assign_minutes + $late_tolerance;

            $attendance_clock_minutes = (int)date('H', strtotime($attendance_clock)) * 60 + (int)date('i', strtotime($attendance_clock));

            $attendance_status = ($attendance_clock_minutes > $allowed_latest_time) ? 'late' : 'present';

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
                
            ], 200); // Created
        }
    }

    public function destroy($id){
        $facelist = FaceEncoding::findOrFail($id);
        $facelist->delete();

        return redirect()->back()->with('success', 'Face deleted successfully');
    }

    // public function updateattendance(Request $request)
    // {
    //     Log::info('Received request to update attendance', $request->all());

    //     // Logika untuk update data attendance di database
    //     $attendance = Attendance::where("id_attendance",$request->attendanceID)->first();
    //     if ($attendance) {
    //         $attendance->clock_in = $request->clockIn;
    //         $attendance->clock_out = $request->clockOut;
    //         $attendance->save();

    //         Log::info('Existing attendance data', [
    //             'attendanceID' => $attendance->id_attendance,
    //             'attendanceID2' => $request->attendanceID,
    //             'clock_in' => $attendance->clock_in,
    //             'clock_out' => $attendance->clock_out
    //         ]);
            
    //         return response()->json(['success' => true, 'message' => 'Attendance updated successfully']);
    //     }

    //     return response()->json(['success' => false, 'message' => 'Attendance not found'], 404);
    // }

    public function updateattendance(Request $request)
    {
        Log::info('Received request to update attendance', $request->all());

        $attendance = Attendance::where("id_attendance", $request->attendanceID)->first();

        if ($attendance) {
            $assignShift = AssignShift::where('id_employee', $attendance->id_employee)
                                    ->where('day', Carbon::parse($attendance->attendance_date)->dayOfWeekIso)
                                    ->first();

            if (!$assignShift || !$assignShift->shift) {
                return response()->json(['success' => false, 'message' => 'No shift assigned for this day.'], 404);
            }

            $shift = $assignShift->shift;
            $clockInShift = Carbon::createFromFormat('H:i:s', $shift->clock_in);
            $clockOutShift = Carbon::createFromFormat('H:i:s', $shift->clock_out);
            $lateTolerance = AttendancePolicy::where("id_company", Auth::user()->id_company)->first()->late_tolerance;

            // Konversi waktu clock-in dan clock-out dari request
            $clockInRequest = Carbon::createFromFormat('H:i:s', $request->clockIn);
            $clockOutRequest = Carbon::createFromFormat('H:i:s', $request->clockOut);

            // Cek keterlambatan
            $allowedLatestTime = $clockInShift->copy()->addMinutes($lateTolerance);
            $attendanceStatus = $clockInRequest->greaterThan($allowedLatestTime) ? 'late' : 'present';

            // Cek apakah clock-out melewati jam shift
            $overtimeMinutes = 0;
            if ($clockOutRequest->greaterThan($clockOutShift)) {
                $overtimeMinutes = $clockOutShift->diffInMinutes($clockOutRequest);
                $attendanceStatus = 'overtime';
            }

            // Hitung total jam kerja
            $totalMinutesWorked = $clockInRequest->diffInMinutes($clockOutRequest);
            $hoursWorked = floor($totalMinutesWorked / 60);
            $minutesWorked = $totalMinutesWorked % 60;
            $dailyTotal = sprintf('%02d:%02d', $hoursWorked, $minutesWorked);

            // Update attendance record
            $attendance->clock_in = $clockInRequest->format('H:i:s');
            $attendance->clock_out = $clockOutRequest->format('H:i:s');
            $attendance->attendance_status = $attendanceStatus;
            $attendance->daily_total = $dailyTotal;
            $attendance->total_overtime = $overtimeMinutes > 0 ? sprintf('%02d:%02d', floor($overtimeMinutes / 60), $overtimeMinutes % 60) : null;
            $attendance->save();

            Log::info('Updated attendance data', [
                'attendanceID' => $attendance->id_attendance,
                'clock_in' => $attendance->clock_in,
                'clock_out' => $attendance->clock_out,
                'attendance_status' => $attendance->attendance_status,
                'daily_total' => $attendance->daily_total,
                'total_overtime' => $attendance->total_overtime
            ]);

            return response()->json(['success' => true, 'message' => 'Attendance updated successfully', 'attendance' => $attendance]);
        }

        return response()->json(['success' => false, 'message' => 'Attendance not found'], 404);
    }


}
