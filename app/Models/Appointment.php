<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
       'patient_id',
       'therapist_id',
       'day',
       'shift',
       'time',
       'status',
       'appointment_date'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function payment()
    {
        return $this->hasOne(Pay::class);
    }

    public function originalReschedulings()
    {
        return $this->hasMany(Rescheduling::class, 'original_appointment_id');
    }

    public function newReschedulings()
    {
        return $this->hasMany(Rescheduling::class, 'new_appointment_id');
    }
}
