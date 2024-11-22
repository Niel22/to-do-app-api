<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){
        
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);


        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){

            return response()->json([
                'message' => 'User logged in successfully',
                'user' => Auth::user(),
            ], 200);
        }else{
            return response()->json([
                'message' => 'Invalid email and password'
            ], 400);
        }
    }
}
