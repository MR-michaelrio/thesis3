<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLeave extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'request_leave_hdrs';

    // Define the primary key
    protected $primaryKey = 'id_request_leave_hdrs';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'leave_time',
        'request_file',
        'id_approver',
        'id_employee',
        'status',
        'leave_type',
        'id_company',
        'leave_start_date',
        'leave_end_date',
        'leave_start_time',
        'leave_end_time',
        'request_description',
        'requested_quota',
        'created_at'
    ];

    // Define relationships
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    public function approver()
    {
        return $this->belongsTo(Employee::class, 'id_approver', 'id_employee');
    }

    public function leavetype()
    {
        return $this->belongsTo(Leave::class, 'leave_type', 'id_leave');
    }

    public function leaeveremaining()
    {
        return $this->belongsTo(AssignLeave::class, 'leave_type', 'id_leave');
    }
}
