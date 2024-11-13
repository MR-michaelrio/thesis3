<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leave'; // Specify table name if it differs from the model's name

    protected $primaryKey = 'id_leave'; // Set primary key

    public $timestamps = true; // Laravel will use created_at and updated_at timestamps

    protected $fillable = [
        'leave_name',
        'category',
        'allocation',
        'valid_date_from',
        'valid_date_end',
        'default_quota',
        'description',
        'id_company',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
