<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getProfile(){

        return Auth::user();

    }

    public function updateProfile(Request $request){

        $rules = [
            'email' => ['required', 'email', 'exists:users'],
            'name' => ['required', 'string'],
            'accountType' => ['required', 'string'],
            'country' => ['required', 'string'],
            'countryCode' => ['required','string'],
            'state' => ['required', 'string'],
            'address' => ['required'],
            'phoneNumber' => ['required']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }

        $data = $validator->validated();

        $user = User::where('email', $data['email'])->first();

        $user->update($data);

        return response()->json([
            'success' => true,
            'status' => 201,
            'message' => 'Profile updated successfully',
        ], 201);
    }
}
