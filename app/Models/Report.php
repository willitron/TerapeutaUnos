<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'report_date',
        'report',
        'report_file',
    ];

    public function patient(){
        return $this->belongsTo(User::class, 'patient_id');
    }
}
