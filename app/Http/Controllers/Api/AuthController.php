<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use JwtAuth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ValidateAndCreatePatient;
use App\User;

class AuthController extends Controller
{
    use ValidateAndCreatePatient;

    public function login(Request $request)
    {
        // Recibimos nuestras credenciales para realizar la autentificación
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('api')->attempt($credentials)) {
            $user = Auth::guard('api')->user();
            $jwt = JwtAuth::generateToken($user);
            $success = true;    
            
            return compact('success','user','jwt');
            // Return successfull sign in response with the generated jwt.
        } else {
            // Return response for failed attempt...
            $success = false;
            $message = 'Invalid credentials';
            return compact('success', 'message');

        }
        
    }
    public function logout()
    {
        Auth:guard('api')->logout();
        $success = true;
        return compact('success');             
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        Auth::guard('api')->login($user);

        $jwt = JwtAuth::generateToken($user);
        $success = true;    
        
        return compact('success','user','jwt');
        // Return successfull sign in response with the generated jwt.
    }
} 
