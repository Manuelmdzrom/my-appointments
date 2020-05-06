<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
 

class PatientController extends Controller

{
      public function index() 
    {
        $patients = User::patients()->paginate(7);
        return view('patients.index', compact('patients'));
    }
  public function create()
    {
        return view('patients.create');
    }
    public function store(Request $request)
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
        User::create(
            $request->only('name', 'email', 'dni', 'address', 'phone')
            + [
                'role' => 'patient',
                'password' => bcrypt($request->input('password'))
            ]
        );
        $notification = 'El paciente se ha registrado correctamente.';
        return redirect('/patients')->with(compact('notification'));
    }

    public function show($id)
    {
        //
    }
    public function edit(User $patient)
    {
        return view('patients.edit', compact('patient'));
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
        $user = User::patients()->findOrFail($id);

        $data = $request->only('name', 'email', 'dni', 'address', 'phone');
        $password = $request->input('password');
        if($password)
           $data['password'] = bcrypt($password);

        $user->fill($data);
        $user->save(); //Update

        $notification = 'La información del paciente se ha actualizado carrectamente.';
        return redirect('/patients')->with(compact('notification'));
    }
    public function destroy(User $patient)
    {
        $patientName = $patient->name;
        $patient->delete();

        $notification = "El paciente $patientName se ha eliminado correctamente.";
        return redirect('/patients')->with(compact('notification'));
    }
}
