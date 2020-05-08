<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'dni', 'address','phone','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','pivot',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    public function specialties()
    {
        //User a Muchas especialidades
        return $this->belongsToMany(Specialty::class)->withTimestamps();
    }
    public function scopePatients($query)
    {
        return $query->where('role', 'patient');
    }

    public function scopeDoctors($query)
    {
        return $query->where('role', 'doctor');
    }
    // $user-> appointmentsAsPatient
    // $user-> asDoctorAppointments   ->attendedAppoointmets
    public function asDoctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function cancelledAppointments()
    {
        return $this->asDoctorAppointments()->where('status','Cancelada');
    }

    public function attendedAppointments()
    {
        return $this->asDoctorAppointments()->where('status','Atendida');
    }

    public function asPatientAppointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
}
