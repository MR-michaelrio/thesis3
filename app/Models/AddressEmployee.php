<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddressEmployee extends Model
{
    use HasFactory;

    protected $table = 'address_employee';
    protected $primaryKey = 'id_address_employee';
    protected $fillable = [
        'country',
        'postal_code',
        'full_address',
        'id_company',
    ];

    // Relasi ke Employee
    public function employee()
    {
        return $this->hasOne(Employee::class, 'id_address_employee', 'id_address_employee');
    }
}
