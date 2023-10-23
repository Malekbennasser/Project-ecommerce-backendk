<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;


class OrderController extends Controller
{
    public function index(){
        $orders= Order::latest()->get();;
        return response()->json([
            'status'=>200,
            'orders'=>$orders
        ]);
    }
}
