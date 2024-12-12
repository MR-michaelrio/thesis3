<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestLeave;
use App\Models\RequestOvertime;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AssignShift;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $currentTime = Carbon::now('Asia/Jakarta');
        $today = $currentTime->format('Y-m-d');
        $currentDay = $currentTime->format('l');

        // Using 'where' for repeated condition on 'id_company' and 'attendance_date'
        

        $attendance = Attendance::where("id_employee",Auth::user()->employee->id_employee)->where("attendance_date", $today)->first();

        if(Auth::user()->role != "admin"){
            $Employee = Employee::with(['user.department'])
                                        ->whereHas('user', function($query) {
                                            $query->where('id_company', Auth::user()->id_company)
                                                ->where('id_department', Auth::user()->id_department);
                                        })
                                        ->count();

            $RequestLeave = RequestLeave::where("id_company",Auth::user()->id_company)
                                        ->where('id_employee', Auth::user()->employee->id_employee)                              
                                        ->count();

            $RequestOvertime = RequestOvertime::where("id_company",Auth::user()->id_company)
                                        ->where('id_employee', Auth::user()->employee->id_employee)                              
                                        ->count();
        }else{
            $RequestLeave = RequestLeave::where('id_company', Auth::user()->id_company)
                                        ->whereDate('created_at', '=', $today)                                    
                                        ->count();

            $RequestOvertime = RequestOvertime::where('id_company', Auth::user()->id_company)
                                        ->whereDate('overtime_date', '=', $today)  
                                        ->count();

            $Employee = Employee::where('id_company', Auth::user()->id_company)->count();
        }

        $dayMapping = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7,
        ];

        $assignshift = AssignShift::with('shift')->where("id_employee", Auth::user()->employee->id_employee)->where("day", $dayMapping[strtolower($currentDay)])->first();

        return view('dashboard', compact('RequestLeave', 'RequestOvertime', 'Employee', 'assignshift', 'attendance'));
    }

    public function calender()
    {
        return view('calender');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
