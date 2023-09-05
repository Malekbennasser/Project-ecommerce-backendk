<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\User ;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index (Request $request){
        $users=User::all();
        return response()->json([
            'status'=>200,
            'users'=>$users,
        ]);
    }
}
