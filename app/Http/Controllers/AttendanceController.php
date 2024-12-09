<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\FaceEncoding;
use App\Models\Shift;
use App\Models\Attendance;
use App\Models\AttendancePolicy;
use App\Models\AssignShift;

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

    public function data()
    {
        $attendance = Attendance::with('employee.user', 'shift')
        ->where('id_company', Auth::user()->id_company)
        ->orderBy('attendance_date', 'desc')  // Sort by 'attendance_date' in ascending order
        ->get();
            // dd($attendance);
        return view('attendance/attendance-data',compact("attendance"));
    }

    /**
     * Show the form for creating a new resource.
     */
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
            $response = $client->post('http://localhost:6002/process_frame', [
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

    public function processFrame(Request $request)
    {
        // Validate the incoming frame (optional)
        $request->validate([
            'image' => 'required|image', // Ensure image is sent
        ]);

        // Get the uploaded image
        $image = $request->file('image');

        // Prepare the image data to send to the Python service
        $imagePath = $image->getRealPath();
        $imageName = $image->getClientOriginalName();

        // Send the image to the Flask service
        $client = new Client();
        try {
            $response = $client->post('http://127.0.0.1:6002/process_frame', [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($imagePath, 'r'),
                        'filename' => $imageName
                    ]
                ]
            ]);

            // Handle the response from the Python service
            $data = json_decode($response->getBody()->getContents(), true);

            // Return the processed data (e.g., detected faces, labels, etc.)
            return response()->json($data);

        } catch (\Exception $e) {
            // Log and return error if something goes wrong
            Log::error('Error processing frame: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing frame'], 500);
        }
    }
    
    public function checkin(Request $request)
    {
        $currentTime = Carbon::now('Asia/Jakarta'); // Get the current time using Carbon

        // Get attendance date and clock-in time from the request or default to the current date/time
        $attendance_date = $request->attendance_date; // Format YYYY-MM-DD
        $attendance_clock = $request->clock; // Default to current time if not provided
        $id_employee = $request->id_employee;
        // Fetch the employee's shift assignment
        $assignshift = AssignShift::where('id_employee', $id_employee)->first();

        // If no shift assignment found, return an error response
        if (!$assignshift) {
            return response()->json([
                'message' => 'No shift assignment found for the employee.',
            ], 201); // Not Found
        }

        $attendance = Attendance::where('id_employee', $id_employee)
                            ->where('attendance_date', $attendance_date)
                            ->first();

        // If attendance exists and clock-out is already recorded, return message
        if ($attendance && $attendance->clock_out) {
            return response()->json([
                'message' => 'Already clocked out!',
            ], 201); // OK
        }

        // Check if the current time is after the employee's clock-out time
        if ($currentTime->format('H:i:s') >= $assignshift->shift->clock_out) {
            if ($attendance) {
                $clockIn = Carbon::createFromFormat('H:i:s', $attendance->clock_in);
                $clockOut = Carbon::createFromFormat('H:i:s', $attendance_clock);
                $dailyTotal = $clockIn->diff($clockOut);

                // Update the attendance record with the clock-out time
                $attendance->clock_out = $attendance_clock;
                // Calculate daily total hours worked
                $attendance->daily_total = sprintf('%02d:%02d', $dailyTotal->h, $dailyTotal->i);
                $attendance->attendance_status = 'present'; // You can set this as 'completed' for clock-out
                $attendance->save();

                // Return a response that the attendance was updated
                return response()->json([
                    'message' => 'Attendance clock-out updated!',
                    'attendance' => $attendance,
                ], 200); // OK
            } else {
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
                ], 201); // OK
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
            ], 201); // Created
        }
    }

    public function getAttendanceData()
    {
        $idCompany = Auth::user()->id_company;
    
        // Ambil data jumlah kehadiran berdasarkan status dan bulan
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
    
        return response()->json($attendanceData);
    }
}
