<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'profile_photo',
        'name',
        'email',
        'password',
        'apellido_paterno',
        'apellido_materno',
        'phone',
        'user_type',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // public function schedules(){
        // return $this->hasMany(Schedule::class);
    // }

    public function notifications()
{
    return $this->hasMany(Notification::class);
}

public function schedules()
{
    return $this->hasMany(Schedule::class, 'patient_id'); // Usa el nombre correcto de la FK si no es 'user_id'
}

public function patientAppointments()
{
    return $this->hasMany(Appointment::class, 'patient_id');
}

// Relación: si el usuario es terapeuta, tiene muchas citas
public function therapistAppointments()
{
    return $this->hasMany(Appointment::class, 'therapist_id');
}
}
