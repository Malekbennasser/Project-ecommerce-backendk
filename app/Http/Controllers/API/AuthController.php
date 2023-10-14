<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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
    
            // Journalisation de la connexion réussie
            Log::info('User logged in', ['user_id' => $user->id, 'email' => $user->email, 'role' => $role]);
    
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Logged In Successfully',
                'role' => $role,
            ]);
        } catch (\Exception $e) {
            // Journalisation de l'échec de la connexion
            Log::warning('Login failed', ['email' => $request->email]);
    
            return response()->json([
                'status' => 401,
                'message' => 'Invalid Credentials',
            ]);
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
