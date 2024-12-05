<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RequestOvertime;

class RequestOvertimeController extends Controller
{
    public function index()
    {
        // Return all overtime records as a collection of resources
        $overtimes = RequestOvertime::all();
        return view('approval.overtime-data', compact('overtimes'));;
    }

    public function create()
    {
        return view('request.overtime-request');
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'overtime_date' => 'nullable|date',
        //     'start' => 'nullable|date',
        //     'end' => 'nullable|date',
        //     'id_employee' => 'required|integer',
        //     'request_description' => 'nullable|string',
        //     'request_file' => 'nullable|string',
        //     'id_attendance' => 'required|integer',
        //     'id_approver' => 'required|integer',
        //     'status' => 'nullable|string',
        //     'id_company' => 'required|integer',
        // ]);

        $overtime = RequestOvertime::create($request->all());

        return view('request.overtime-request');
    }
}
