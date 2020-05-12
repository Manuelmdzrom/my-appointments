<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Ruta para la autentificación
Route::post('/login', 'AuthController@login');

//Public resources
//JSON
Route::get('/specialties', 'SpecialtyController@index');
Route::get('/specialties/{specialty}/doctors', 'SpecialtyController@doctors');
Route::get('/schedule/hours', 'ScheduleController@hours');

Route::middleware('auth:api')->group(function () {

    Route::get('/user', 'UserController@show');
    Route::post('/logout', 'UserController@logout');

    //post appointment
    Route::get('/appointments', 'AppointmentController@index');
    //appointments
    Route::post('/appointments', 'AppointmentController@store');
    
});