<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelledAppointment extends Model
{
    public function cancelled_by() //cancelled_by_id
    {
        //nelongsTo Cancellation N - 1 User
        return $this->belongsTo(User::class);
    }
}
