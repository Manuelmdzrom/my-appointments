<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Appointment;

use App\Http\Requests\StoreAppointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $user = Auth::guard('api')->user();
        return $user->asPatientAppointments()
        ->with([
            'specialty' => function ($query){
            $query->select('id','name');
        },
            'doctor' => function ($query){
            $query->select('id','name');
        }
         ])
        ->get([
            "id",
            "description",
            "specialty_id",
            "doctor_id",
            "schedule_date",
            "schedule_time",
            "type",
            "created_at",
            "status"
        ]);
        
        return $appointments;
    }
    public function store(StoreAppointment $request)
    {
        $patientId = Auth::guard('api')->id();
        $appointment = Appointment::createForPatient($request, $patientId);
        if ($appointment)
            $success = true;
        else        
            $success = false;

        return campact('success');
    }    
}
