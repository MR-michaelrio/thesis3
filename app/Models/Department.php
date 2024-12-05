<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = "department";
    protected $primaryKey = "id_department";
    protected $fillable = [
        'department_name', 'department_code', 'id_parent', 'id_supervisor', 'description', 'id_company'
    ];

    public function positions()
    {
        return $this->hasMany(DepartmentPosition::class, 'id_department');
    }

    public function supervisor()
    {
        return $this->belongsTo(Employee::class, 'id_supervisor', 'id_employee');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'id_parent', 'id_department');
    }
}
