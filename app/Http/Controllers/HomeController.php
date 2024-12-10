<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestLeave;
use App\Models\RequestOvertime;
use App\Models\Employee;
use App\Models\Attendance;
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
        $today = $currentTime->format('Y-m-d'); // Get today's date in 'Y-m-d' format

        // Using 'where' for repeated condition on 'id_company' and 'attendance_date'
        $RequestLeave = RequestLeave::where('id_company', Auth::user()->id_company)
                                        ->whereDate('created_at', '=', $today)                                    
                                        ->count();

        $RequestOvertime = RequestOvertime::where('id_company', Auth::user()->id_company)
                                        ->whereDate('overtime_date', '=', $today)  
                                        ->count();

        $Employee = Employee::where('id_company', Auth::user()->id_company)->count();
        // Passing the variables to the view
        return view('dashboard', compact('RequestLeave', 'RequestOvertime', 'Employee'));
    }

    public function calender()
    {
        return view('calender');
    }
}
