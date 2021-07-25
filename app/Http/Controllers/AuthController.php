<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Social;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function Register(Request $request){


        $validator = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json([
                "validation_errors" => $validator->messages(),
            ]);
        }else{

            $request['password'] = Hash::make($request['password']);
            $user = User::create(request()->all());

            return response()->json([
                "status" => 200,
                "username" => $user->name,
                "user" => $user,
                "message" => "Sign up Successfully", 
            ]);
        }
    
    }


    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|max:191',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                "validation_errors" => $validator->messages(),
            ]);
        }else{

            $user = User::where('email', $request['email'])->first();

            if(!$user || !Hash::check($request['password'], $user->password)){
                return response()->json([
                    "status" => 401,
                    "message" => "Invalid Credentials"
                ]);
            } else { 
                
                $token = $user->createToken($user->id.'_Token')->plainTextToken;

                return response()->json([
                    "status" => 200,
                    "user" => $user , 
                    "username" => $user->name,
                    "token" => $token,
                    "message" => "Sign in Successfully", 
                ]);
            }
        }
        
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "status" => 200,
            "message" => "success logout"
        ]);
    }


    public function googleAuth (Request $request)
    {
        return Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
    }

    public function googleCallback(Request $request)
    {
        $socialite = Socialite::driver('google')->stateless()->user();

        // save user & save provider 
        $user = User::where("email",$socialite->email)->first();

        // save user data
        if($user){
            $social = Social::where("user_id",$user->id)->first();
            if($social){ 
                $token =$user->createToken($user->id.'_Token')->plainTextToken;
                $url = env('AUTH_URL').'/authenticate?auth_token='.$token.'';
               
                // redirect ke react dengan parameter token
                // dekat page yg kau redirect ni , kau aksess pareamter , save parameter local storgae
                return redirect()->away($url);

            }else{
                return response()->json([
                    "status" => 400,
                    "message" => "This email was used to create account with email registration",
                ]);
            }
            // kalau xda , kau cakap kat dia dah registr pakai eamil , guna password nak login
        }else{

            $user = User::create([
                'email' => $socialite->email,
                'name' => $socialite->name,
                'password' => Hash::make($socialite->token)        
            ]);

            $social = Social::create([
                'user_id' => $user->id,
                'provider_name' =>'google',
                'provider_id' => $socialite->id,
            ]);

            // save dalam provider

            $token =$user->createToken($user->id.'_Token')->plainTextToken;
            $url = env('AUTH_URL').'/authenticate?auth_token='.$token.'';

            return redirect()->away($url);
        }

    }
    

}