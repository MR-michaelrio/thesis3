<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestOvertime;
use Auth;  
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
        $overtime = RequestOvertime::create($request->all());

        return view('request.overtime-request');
    }
}
