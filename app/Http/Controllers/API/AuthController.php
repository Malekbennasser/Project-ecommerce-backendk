<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name' =>'required|string|max:50',
             'email' => "required|email|max:100|unique:users,email",
             'password' => 'required|string|min:8',

        ]);
        if ($validator->fails()){
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }else{

            $user = User::create([
                'name'=> $request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

           $token = $user->createToken($user->email.'_token')->plainTextToken;
           return response()->json([
            'status'=>201,
            'username'=>$user->name,
            'token'=>$token,
            'message'=>'Registered Successfully',

        ]);

        }
    }


    public function login(Request $request)
{
    // Validation des données d'entrée
    $validator = Validator::make($request->all(), [
        'email' => "required|email",
        'password' => 'required|string|min:8',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'message' => 'Validation failed',
            'validation_errors' => $validator->messages(),
        ]);
    }

    // Tentative de connexion sécurisée
    try {
        $user = User::where('email', $request->email)->firstOrFail();

        if (! Hash::check($request->password, $user->password)) {
            throw new \Exception('Invalid credentials');
        }

        if ($user->role_as == 1) {
            $role = 'admin';
            $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken;
        } else {
            $role = 'user';
            $token = $user->createToken($user->email.'_Token', [])->plainTextToken;
        }

        return response()->json([
            'status' => 200,
            'username' => $user->name,
            'token' => $token,
            'message' => 'Logged In Successfully',
            'role' => $role,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 401,
            'message' => 'Invalid Credentials',
        ]);
    }
}



    public function sendEmailPasswordReset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100'
        ]);

        if ($validator->fails()){
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ],401);

           
        }

      
        $user = User::getEmail($request->email);

     
        if (!empty($user)) {
           
            $user->remember_token = Str::random(64);
            $user->save();

       
            Mail::to($user->email)->send(new ResetPasswordMail($user));

            return response()->json(['message' => 'Please check your email to reset your password'], 201);
        } else {
           
            return response()->json(['error' => 'Email not found in the system.'], 401);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:4',
            'token' => 'required|string',
        ]);

        if ($validator->fails()){
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ],401);

           
        }

        $user = User::getToken($request->token);

        if (!empty($user)) {

          
            $newPassword = $request->password;
            $user->password = Hash::make($newPassword);
            $user->remember_token = Str::random(64);
            $user->save();

            return response()->json(['message' => 'Password changed successfuly'], 201);
        } else {
            return response()->json(['message' => 'Wrong token'], 401);
        }
    }













    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'logged out Successfully'
        ]);
    }
}
