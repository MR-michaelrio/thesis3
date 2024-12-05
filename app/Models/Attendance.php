<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    //
    use HasFactory;

    protected $table = 'attendance'; // Specify the table name if it's not pluralized automatically
    protected $primaryKey = 'id_attendance'; // Define the primary key

    // Define which fields are mass assignable
    protected $fillable = [
        'id_employee',
        'attendance_date',
        'shift_id',
        'clock_in',
        'clock_out',
        'daily_total',
        'total_overtime',
        'attendance_status',
        'id_company',
    ];

    // Define the relationship with Employee (assuming you have an Employee model)
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    // Define the relationship with Shift (assuming you have a Shift model)
    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
