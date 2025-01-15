<?php

namespace App\Http\Controllers;

use App\Models\AttendancePolicy;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
class AttendancePolicyController extends Controller
{
    public function index()
    {
        // Ambil data pertama dari table
        $policy = AttendancePolicy::where("id_company",Auth::user()->id_company)->first();

        // Jika belum ada data, buat data kosong untuk ditampilkan di form
        if (!$policy) {
            $policy = new AttendancePolicy();
        }

        return view('attendance.attendance-policy', compact('policy'));
    }

    public function updateOrCreate(Request $request)
    {
        // Cari apakah sudah ada Attendance Policy untuk perusahaan tersebut
        $policy = AttendancePolicy::where('id_company', Auth::user()->id_company)->first();

        // Jika belum ada, buat baru; jika ada, lakukan update
        if ($policy) {
            // Update kebijakan yang sudah ada
            $policy->update($request->only(['late_tolerance', 'overtime_start', 'overtime_end']));
        } else {
            // Buat kebijakan baru
            AttendancePolicy::create(array_merge(
                $request->only(['late_tolerance', 'overtime_start', 'overtime_end']),
                ['id_company' => Auth::user()->id_company]
            ));
        }

        return redirect()->route('attendance_policy.index')->with('success', 'Attendance Policy updated successfully!');
    }


}
