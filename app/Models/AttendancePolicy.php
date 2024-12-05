<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancePolicy extends Model
{
    use HasFactory;

    protected $table = 'attendance_policy';
    protected $primaryKey = 'id_attendance_policy';
    public $timestamps = true;

    protected $fillable = [
        'late_tolerance',
        'overtime_start',
        'overtime_end',
        'id_company',
    ];
}
