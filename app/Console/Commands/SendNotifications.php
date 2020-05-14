<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Appointment;

class SendNotifications extends Command
{
    protected $signature = 'fcm:send';

    protected $description = 'Envias mensajes vía FCM';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Buscando citas médicas confirmadas en las próximas 24 horas.');
        //01 December -> 02 December ()
        // 3pm        -> 3pm
        //hora actual 
        //2018-12-01 14:00.00
        $now = Carbon::now();

        // schedule_date 2018-12-01
        // schedule_time 15:00:00           hActual -3 <= schedule_time < hActual + 3 

        $appointmentsTomorrow = $this->getAppointments24Hours($now);

        foreach($appointmentsTomorrow as $appointment){
            $appointment->patient->sendFCM('No olvides tu cita mañana a esta hora.');
            $this->info('Mensaje FCM enviado 24h antes al Paciente (ID:) ' . $appointment->patient_id);
        }

        $appointmentsNextHours = $this->getAppointmentsNextHours($now);

        foreach($appointmentsNextHours as $appointment){
            $appointment->patient->sendFCM('Tienes una cita en una hora. Te esperamos.');
            $this->info('Mensaje FCM enviado faltando 1h al Paciente (ID:) '. $appointment->patient_id);
        }
    }

    private function getAppointments24Hours($now)
    {
        return Appointment::where('status', 'Confirmada')
        ->where('schedule_date', $now->addDay()->toDateString())
        ->where('schedule_time', '>=' , $now->copy()->subMinutes(3)->toTimeString())
        ->where('schedule_time', '<' , $now->copy()->addMinutes(2)->toTimeString())
        ->get(['id', 'schedule_date', 'schedule_time', 'patient_id'])
        ->toArray();
    }
    private function getAppointmentsNextHours($now)
    {
        return Appointment::where('status', 'Confirmada')
        ->where('schedule_date', $now->addHour()->toDateString())
        ->where('schedule_time', '>=' , $now->copy()->subMinutes(3)->toTimeString())
        ->where('schedule_time', '<' , $now->copy()->addMinutes(2)->toTimeString())
        ->get(['id', 'schedule_date', 'schedule_time', 'patient_id'])
        ->toArray();
    }
}
