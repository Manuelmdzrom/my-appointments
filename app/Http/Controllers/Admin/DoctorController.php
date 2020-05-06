<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Specialty;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = User::doctors()->paginate(7);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        return view('doctors.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'dni' => 'nullable|digits:8',
            'address' => 'nullable|min:5',
            'phone' => 'nullable|min:6'
        ];
        $this->validate($request, $rules);
        
        // mass assigment
        $user = User::create(
            $request->only('name', 'email', 'dni', 'address', 'phone')
            + [
                'role' => 'doctor',
                'password' => bcrypt($request->input('password'))
            ]
        );
        //aceeder a las especialidades atraves del modelo user
        $user->specialties()->attach($request->input('specialties'));

        $notification = 'El médico se ha registrado correctamente.';
        return redirect('/doctors')->with(compact('notification'));
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $doctor = User::doctors()->findOrFail($id);
        $specialties = Specialty::all();

        $specialty_ids = $doctor->specialties()->pluck('specialties.id');
        return view('doctors.edit', compact('doctor','specialties','specialty_ids'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email',
            'dni' => 'nullable|digits:8',
            'address' => 'nullable|min:5',
            'phone' => 'nullable|min:6'
        ];
        $this->validate($request, $rules);
        
        // mass assigment
        $user = User::doctors()->findOrFail($id);

        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
        if($password)
           $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save(); //Update

        $user->specialties()->sync($request->input('specialties'));

        $notification = 'La información del médico se ha actualizado carrectamente.';
        return redirect('/doctors')->with(compact('notification'));
    }

    public function destroy(User $doctor)
    {
        $doctorName = $doctor->name;
        $doctor->delete();

        $notification = "El médico $doctorName se ha eliminado correctamente";
        return redirect('/doctors')->with(compact('notification'));
    }
}
