<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentPosition extends Model
{
    use HasFactory;
    protected $table = 'department_position';
    protected $primaryKey = "id_department_position";
    protected $fillable = [
        'position_title', 'position_description', 'id_department', 'id_company'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department');
    }

}
