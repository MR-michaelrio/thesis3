<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignShift extends Model
{
    //
    use HasFactory;
    protected $table = 'assign_shift';

    protected $primaryKey = 'id_assign_shift';
    public $timestamps = false;

    protected $fillable = [
        'id_employee',
        'id_shift',
        'day',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'id_shift', 'id_shift');
    }
}
