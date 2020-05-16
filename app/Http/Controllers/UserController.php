<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function edit()
    {   
        $user = auth()->user();
        return view('profile', compact('user'));
    }
    public function update(Request $request)
    {
        $user = auth()->user();
        $user->name = $request->name;  //Tambien // $request->input('')
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();

        $notification = 'Los datos han sido enviados correctamente.';
        return back()->with(compact('notification'));  // session ('notification')
    }
}
