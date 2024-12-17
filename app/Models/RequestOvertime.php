<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestOvertime extends Model
{
    use HasFactory;

    protected $table = 'request_overtime';

    protected $primaryKey = 'id_overtime';

    protected $fillable = [
        'overtime_date',
        'start',
        'end',
        'id_employee',
        'request_description',
        'request_file',
        'id_attendance',
        'id_approver',
        'status',
        'id_company'
    ];

    // Add relationships if needed
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    // public function approver()
    // {
    //     return $this->belongsTo(Employee::class, 'id_approver');
    // }

    // public function attendance()
    // {
    //     return $this->belongsTo(Attendance::class, 'id_attendance');
    // }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
