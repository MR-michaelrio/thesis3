<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shift';

    protected $primaryKey = 'id_shift';

    protected $fillable = [
        'shift_name',
        'clock_in',
        'clock_out',
        'shift_description',
        'id_company',
    ];
}
