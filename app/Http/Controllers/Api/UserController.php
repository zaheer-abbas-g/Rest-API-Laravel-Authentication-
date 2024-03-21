<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegister;
use App\Models\PasswordResetToken;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function register(UserRegister $request){

        /**
         * @author zaheer 
         *  User Registration
         */
         
            $user_email = User::where('email',$request->email)->first();
            
            if(!empty($user_email->email)){

                return response()->json([
                    'message' => 'Email is already Exists',
                    'status'  => 'failed'
                ],200);
            }
           
           
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            $token = $user->createToken($request->email)->plainTextToken;

            return response()->json([
                'token'   => $token,
                'message' => 'User Registeration Successfully',
                'status'  => 'success'
            ],201); 
    }

    public function login(Request $request){

            $request->validate([
                'email' => "required|email",
                "password" => "required"
            ]);
            $user = User::where('email',$request->email)->first();

            if($user && Hash::check($request->password,$user->password)){  
                
            $token = $user->createToken($request->email)->plainTextToken;  

            return response()->json(
                    [
                        'token' => $token,
                        'message' => "User Login Successfully",
                        'status' => 'success'
                    ],
                200);
            }else{
                return response()->json(
                    [
                        "message" => "The Provided Credentials are incorrec",
                        "status" => "failed"
                    ],401
                );
            }
      
           
    }


    public function logout(){

            auth()->user()->tokens()->delete();

            return response()->json([
                        'message' => "User logout Successfully",
                        'status' => "success",
                    ],200);
    }

    public function logedin_user(){

            $loged_user = auth()->user();   
            return response()->json([
                    'user' => $loged_user,
                    'message' => "logged user",
                    "status"  => "success"
            ],200);
    }


    public function change_password(Request $request){

            $request->validate(
            [
                'password' => "required|confirmed"
            ]
            );
 
            $loged_user = auth()->user();
            $loged_user->password = Hash::make($request->password);
            $loged_user->save();
            return response()->json(
                [
                    "user" => $loged_user,
                   "message" => "Your Password changed successfully",
                   "status"  => "success",
                ]
            );
    }


    public function password_reset_by_email(Request $request){

            $request->validate([
                'email' => "required|email",
            ]);
            $email =   $request->email;
            $user = User::where("email", $email)->first();
            if(!$user){
                return response()->json(
                    [
                        "message" => "Email not exists",
                        "status"  => "failed"
                    ],404
                );
            }


            $token = Str::random(60);

            PasswordResetToken::create([
                    "email" => $email,
                    'token'  => $token,
                    "created_at" => Carbon::now()
            ]);

            // dump("http://127.0.0.1:3000/api/user/reset/".$token);

            Mail::send('reset',["token"=> $token],function(Message $message)use($email){
                $message->subject("Reset Your Password");
                $message->to($email);
            });
            return response()->json([
                
                "message" => "Password reset email sent.... Check your email",
                "status"  => "success",
            ],200);
    }

    public function reset(Request $request,$token){
        
        $timer = Carbon::now()->subMinute(2)->toDateTimeString();

        PasswordResetToken::where('created_at',"<=",$timer)->delete();

        $request->validate([
            "password" => "required|confirmed"
        ]);

       $user_token = PasswordResetToken::where('token',$token)->first();
       if (!$user_token) {
        return response()->json([
            "message" => "Invalid Token or expired",
            "status"  => "failed"
        ],404);
       }

       $user = User::where("email",$user_token->email)->first();
       $user->password = Hash::make($request->password);
       $user->save();
    
       PasswordResetToken::where("email",$user->email)->delete();

       return response()->json([
        "message" => "password reset successfully",
        "status"  => "success",
       ],200);
    }
}
