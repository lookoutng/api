<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
    
    
    class UserController extends Controller
    {
        
        #########
        function store(Request $request)
        {
            $fields = $request->validate([
                'tel' => 'required|string',
            ]);

            extract($fields);

            $user = User::FirstOrCreate([
                'tel' => $tel
            ]);
           
            $token = $user->createToken('userToken')->plainTextToken;
    
            $response = [
                'user' => $user,
                'token' => $token,
                'message' => "User Registered Succesfully"
            ];
    
            return response($response, 201);
        }
    
        function logout(Request $request){

            auth()->user()->tokens()->delete();
    
            $response = [
                'messsage' => 'Logged out Succesfully'
            ];
            return response($response,201);
        }
    
        function update(Request $request)
        {
            $user = auth()->user();
            $fields = $request->validate([
                'email' => 'string|unique:users,email',
                'username' => 'string|unique:users,username',
                'dp' => 'string',
                'status' => 'Integer',
            ]);
    
            extract($fields);
            $user->update(
                [
                    'username' => @$username ?? $user->username,
                    'email' => @$email ?? $user->email,
                    'dp' => @$dp ?? $user->dp,
                ]
            );

            $response = [
                'user' => $user->refresh(),
                'message' => 'update successfull'
            ];
            
            return response($response,201);
    
        }
        function lastlocation(){

            $location = Location::where('type',0)->where('parent_id',$user->id)->orderby('id','DESC')->first();
            $response = [
                'lat' => $location->lat,
                'long' => $location->long
            ];

            return response($response,201);
        }

        function show(Request $request){
            $user = auth()->user();
            $response = [
                'user' => $user
            ];

            return response($response, 201);
        }
}
