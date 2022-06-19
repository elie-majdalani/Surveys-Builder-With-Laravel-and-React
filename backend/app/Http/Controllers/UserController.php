<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserController extends Controller
{
    public function signUp(Request $request){
        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->input(key: 'password'));
        $user->user_type_id = 0;
        $user->save();

        return response()->json([
            "status" => "Success",
            "users" => $user
        ], 200);
    }
    
    public function logIn(Request $request){
        $user = User::where('email', $request->email)->first();
        if($user){
            if(Hash::check($request->password, $user->password)){
                return response()->json([
                    "status" => "Success",
                    "users" => $user
                ], 200);
            }
            else{
                return response()->json([
                    "status" => "Error",
                    "message" => "Password is incorrect"
                ], 400);
            }
        }
        else{
            return response()->json([
                "status" => "Error",
                "message" => "User not found"
            ], 400);
        }
    }
}
