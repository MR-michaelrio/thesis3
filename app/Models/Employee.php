<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';
    protected $primaryKey = 'id_employee';
    protected $fillable = [
        'profile_picture',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'marital',
        'religion',
        'place_of_birth',
        'date_of_birth',
        'id_address_employee',
        'id_users',
        'id_company',
    ];

    // Relasi ke AddressEmployee
    public function address()
    {
        return $this->belongsTo(AddressEmployee::class, 'id_address_employee', 'id_address_employee');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id_user');
    }

    public function supervisedDepartments()
    {
        return $this->hasMany(Department::class, 'id_supervisor', 'id_employee');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department');
    }

    public function position()
    {
        return $this->belongsTo(DepartmentPosition::class, 'id_department_position', 'id');
    }

    public function RequestLeave()
    {
        return $this->belongsTo(RequestLeave::class, 'id_employee', 'id_approver');
    }

    public function faceEncodings()
    {
        return $this->hasMany(FaceEncoding::class, 'id_employee', 'id_employee');
    }

    public function assignShifts()
    {
        return $this->hasMany(AssignShift::class, 'id_employee', 'id_employee');
    }

}
