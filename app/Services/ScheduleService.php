<?php namespace App\Services;

use App\Interfaces\ScheduleServiceInterface;
use App\WorkDay;
use Carbon\Carbon;
use App\Appointment;


class ScheduleService implements ScheduleServiceInterface
{
    public function isAvailableInterval($date, $doctorId ,Carbon $start){
        
        $exists = Appointment::where('doctor_id', $doctorId)
        ->where('schedule_date', $date)
        ->where('schedule_time', $start->format('H:i:s'))
        ->exists();

            return !$exists; // Disponible si actualmente no hay cita para esa fecha y hora
    }

    private function getDayFromDate($date)
    {
        $dateCarbon = new Carbon($date);
        //Dayofweek 
        //Carbon : 0 Sunday - 6 Saturday
        // WorkDay : 0 Monday - 6 Sunday
        $i = $dateCarbon->dayOfWeek;
        $day = ($i==0 ? 6 : $i-1);
        return $day;
    }
    public function getAvailableIntervals($date, $doctorId)
    {
        $workDay = WorkDay::where('active', true)
        ->where('day', $this->getDayFromDate($date))
        ->where('user_id', $doctorId)
        ->first([
            'morning_start', 'morning_end',
            'afternoon_start', 'afternoon_end'
        ]);
        
        if($workDay){
             //dd($workDay);
            $morningStart = new Carbon();
            $morningEnd = new Carbon();

            $morningIntervals = $this->getIntervals(
                $workDay->morning_start,$workDay->morning_end,
                $date, $doctorId
            );   
            $afternoonIntervals = $this->getIntervals(
                $workDay->afternoon_start,$workDay->afternoon_end,
                $date, $doctorId
            );
                  
        }else{
            $morningIntervals = [];
            $afternoonIntervals =[];
        }
       
        $data =[];
        $data['morning'] = $morningIntervals;
        $data['afternoon'] = $afternoonIntervals;
        
        return $data;
    }
    private function getIntervals($start, $end, $date, $doctorId){
        $start = new Carbon($start);
        $end = new Carbon($end);

        $intervals = [];
        while ($start < $end) {
            $interval = [];
    
            $interval['start'] = $start->format('g:i A');
            //No existe una cita para esta hora con este médico
            $available = $this->isAvailableInterval($date, $doctorId, $start);
            
            $start->addMinutes(30);
            $interval['end'] = $start->format('g:i A');
            
            if($available){
            $intervals []= $interval;

            }
        }
        return $intervals;        
    }       
}