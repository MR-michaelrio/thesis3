<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    //
    use HasFactory;
    protected $table = 'company';

    protected $primaryKey = 'id_company';

    public $timestamps = true;

    protected $fillable = [
        'logo',
        'company_name',
        'company_code',
        'country',
        'full_address',
        'postal_code',
        'company_email',
        'company_phone',
        'is_active',
        "pic"
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_company');
    }
    public function Pic()
    {
        return $this->belongsTo(User::class, 'pic', 'id_user');
    }
}
