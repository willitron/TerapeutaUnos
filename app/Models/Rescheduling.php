<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rescheduling extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_original_appointment',
        'id_new_appointment',
        'reason',
    ];

    public function originalAppointment()
    {
        return $this->belongsTo(Appointment::class, 'original_appointment_id');
    }

    public function newAppointment()
    {
        return $this->belongsTo(Appointment::class, 'new_appointment_id');
    }
}
