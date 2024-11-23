<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function store(Request $request){
        
        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }

        $data = $validator->validated();

        if(Auth::attempt($data)){

            $user = Auth::user();

            $token = $user->createToken('todo-app')->plainTextToken;

            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token,
                'user' => $user
            ], 201);

        }else{
            return response()->json([
                'status' => 400,
                'message' => 'Invalid email and password'
            ], 400);
        }
    }

    public function create(Request $request){
        
        $rules = [
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
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

        $user = User::create($data);

        if($user){
            return response()->json([
                'message' => 'User Account Created successfully, You can now proceed to log in',
                'user' => $user
            ], 201);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Problem occured while creating account'
        ], 400);
    }

    public function forgot(Request $request){

        $rules = [
            "email" => ['required', 'exists:users']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }

        $data = $validator->validated();

        $otp = rand(10000, 99999);

        $user = User::where('email', $data['email'])->first();



        if($user->otp){
            $user->otp()->update([
                'otp' => $otp
            ]);

        }else{
            $user->otp()->create([
                'otp' => $otp
            ]);
        }

        return response()->json([
            'status' => 201,
            'otp' => $otp,
            'message' => 'Check your email for the 5 digit otp to reset your password',
        ], 201);

    }

    public function confirm(Request $request){

        $rules = [
            "email" => ['required', 'exists:users'],
            'otp' => ['required']
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return $validator->errors();
        }

        $data = $validator->validated();

        $user = User::where('email', $data['email'])->first();


        if($user->otp->otp == $data['otp']){

            return response()->json([
                'status' => 201,
                'token' => encrypt($user->otp->otp),
                'message' => "You can now proceed to enter your new password",
            ], 201);
        }

        return response()->json([
            'status' => 400,
            'message' => "The otp you enter is incorrect, check your email to confirm the correct otp or request for new otp",
        ], 400);

    }

    public function reset(Request $request, $token){
        $rules = [
            "email" => ['required', 'exists:users'],
            'newPassword' => ['required', 'min:8']
        ];

        
        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return $validator->errors();
        }
        
        $data = $validator->validated();
        
        $user = User::where('email', $data['email'])->first();

        if($user->otp->otp == decrypt($token)){

            $user->update([
                'password' => $data['newPassword']
            ]);

            $user->otp()->delete();

            return response()->json([
                'status' => 201,
                'message' => "Password reset successfull",
            ], 201); 

        }

        return response()->json([
            'status' => 400,
            'message' => "Unable to change password, request for otp before you can change your password",
        ], 400);

    }

    public function logout(Request $request){
        
        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'status' => 201,
            'message' => "User Logged out",
        ], 201); 
    }

}
