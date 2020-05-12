<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    protected $fillable = [
        'description',
        'specialty_id',
        'doctor_id',
        'patient_id',
        'schedule_date',
        'schedule_time',
        'type'
    ];

    protected $hidden = [
        'specialty_id', 'doctor_id', 'schedule_time'
    ];

    protected $appends = [
        'schedule_time_12'
    ];
    /*
    protected $dates = [
        'schedule_time' // create FormFormat
    ]*/
    // N $appointment->specialty 1
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
     // N $appointment->doctor 1
     public function doctor()
     {
         return $this->belongsTo(User::class);
     }
     // N $appointment->patient 1
     public function patient()
     {
         return $this->belongsTo(User::class);
     }

     //Accesor
     //$appointment->schedule_time_12
     public function getScheduleTime12Attribute()
     {
        return (new Carbon($this->schedule_time))
        ->format('g:i A');
     }

     //Appointment1 to 1 CacelledApoointment
     // $appointment->cancellation->justification
     public function cancellation()
     {
        return $this->hasOne(CancelledAppointment::class);
     }
}
