<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceEncoding extends Model
{
    //
    use HasFactory;

    protected $table = 'face_encoding';
    protected $primaryKey = 'id_face_security';
    protected $fillable = [
        'id_employee',
        'encoding_data',
        'id_company'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_employee', 'id_employee');
    }
}
