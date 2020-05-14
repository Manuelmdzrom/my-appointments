<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'dni', 'address','phone','role'
    ];

    protected $hidden = [
        'password', 'remember_token','pivot',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    public static $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ];

    public static function createPatient(array $data)
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'patient'
        ]);
    }

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
    public function sendFCM($message)
    {
        if (!$this->device_token)
        return;

        return fcm()->to([
                $this->device_token
            ]) // $recipients must an array
            ->priority('high')
            ->timeToLive(0)
            ->notification([
                'title' => config('app.name'),
                'body' => $message,
            ])->send();
    }
}
