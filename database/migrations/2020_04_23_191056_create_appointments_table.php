<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('description');
    
            //FK Specialty
            $table->unsignedInteger('specialty_id');
            $table->foreign('specialty_id')->references('id')->on('specialties');

            //FK Doctor
            $table->unsignedInteger('doctor_id');
            $table->foreign('doctor_id')->references('id')->on('users');

            //FK patient
            $table->unsignedInteger('patient_id');
            $table->foreign('patient_id')->references('id')->on('users');
   
            $table->date('schedule_date');
            $table->time('schedule_time');

            $table->string('type');
            //Reservada, Confirmada, cancelada
            $table->string('status')->default('Reservada');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
