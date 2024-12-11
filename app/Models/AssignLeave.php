<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class AssignLeave extends Model
{
    //
    use HasFactory;
    protected $table = 'assign_leave';

    protected $primaryKey = 'id_assign_leave';
    public $timestamps = false;

    protected $fillable = [
        'id_employee',
        'id_leave',
        'quota',
        'remaining',
        'id_company',
    ];

    // Define relationships

    // Each AssignLeave belongs to one Employee
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee');
    }

    // Each AssignLeave belongs to one Leave
    public function leave()
    {
        return $this->belongsTo(Leave::class, 'id_leave');
    }
}
