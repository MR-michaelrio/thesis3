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
        // Validasi input
        $request->validate([
            'late_tolerance' => 'nullable|integer',
            'overtime_start' => 'nullable|integer',
            'overtime_end' => 'nullable|integer',
        ]);

        // Ambil user yang sedang login
        $user = User::where("id_user", Auth::user()->id_employee)->first();

        // Pastikan user ditemukan dan memiliki 'id_company'
        if (!$user || !$user->id_company) {
            return redirect()->route('attendance_policy.index')->with('error', 'User or company not found!');
        }

        // Buat atau update data
        AttendancePolicy::updateOrCreate(
            // Kondisi pencarian data (primary key)
            ['id_attendance_policy' => $request->id_attendance_policy],

            // Data yang akan dibuat atau diperbarui
            array_merge(
                $request->only(['late_tolerance', 'overtime_start', 'overtime_end']),
                ['id_company' => $user->id_company]
            )
        );

        return redirect()->route('attendance_policy.index')->with('success', 'Attendance Policy updated successfully!');
    }

}
