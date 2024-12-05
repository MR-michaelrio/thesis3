<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\FaceEncoding;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Auth;

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
        return view('attendance/attendance-data');
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
        $attendance_date = $request->attendance_date ?? Carbon::now()->toDateString();
        $clock_in = $request->clock_in ?? Carbon::now(); // Get current time (YYYY-MM-DD HH:MM:SS)
        
        // Create a new attendance record
        $attendance = Attendance::create([
            'id_employee' => $request->id_employee,
            'attendance_date' => $attendance_date,
            'shift_id' => 1,
            'clock_in' => $clock_in,
            'attendance_status' => $request->attendance_status,
            'id_company' => Auth::user()->id_company,
        ]);

        // Return response
        return response()->json([
            'message' => 'Attendance successfully stored!',
            'attendance' => $attendance,
        ], 201);
    }
}
