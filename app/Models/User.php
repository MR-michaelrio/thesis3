<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'email',
        'password',
        'id_department',
        'id_department_position',
        'supervisor',
        'start_work',
        'stop_work',
        'role',
        'phone',
        'emergency_name',
        'emergency_relation',
        'emergency_phone',
        'id_company',
        'identification_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'id_users', 'id_user');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department');
    }

    
    public function position()
    {
        return $this->belongsTo(DepartmentPosition::class, 'id_department_position', 'id_department_position');
    }

    public function supervisior()
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'id_users');
    }

    
}
