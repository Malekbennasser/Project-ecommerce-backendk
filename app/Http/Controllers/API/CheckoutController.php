<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function placeorder (Request $request){

        if(auth('sanctum')->check()){

            $validator = Validator::make($request->all(),[
                'firstname'=>'required|string|max:100',
                'lastname'=>'required|string|max:100',
                'phone'=>'required|numeric',
                'email'=>'required|email|max:100',
                'address'=>'required|string|max:255',
                'city'=>'required|string|max:255',
                'state'=>'required|string|max:255',
                'zipcode'=>'required|string',
            ]);

          if ( $validator->fails()){

            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages(),
            ]);

          }else
          {
            

            $user_id= auth('sanctum')->user()->id;

            $order = new Order;
            $order->user_id= $user_id;
            $order->firstname =$request->firstname;
            $order->lastname =$request->lastname;
            $order->phone =$request->phone;
            $order->email =$request->email;
            $order->address =$request->address;
            $order->city =$request->city;
            $order->state =$request->state;
            $order->zipcode =$request->zipcode;

            $order->payment_mode = $request->payment_mode;
            $order->payment_id = $request->payment_id;
            $order->tracking_no ='thestore'.rand(1111,9999);
            $order->save();

    
            $orderitems =[];

            $cart =Cart::where('user_id',$user_id)->get();



            foreach($cart as $item){

                $orderitems [] =[
                    'product_id'=> $item->product_id,
                    'qty'=>$item->product_Qty,
                    'price'=>$item->product->selling_price,
                ];

                $item->product->update([
                    'qty'=>$item->product->qty - $item->product_Qty 

                ]);


            }

            $order->orderitems()->createMany($orderitems);

            Cart::destroy($cart);





            return response()->json([
                'status'=>200,
                'message'=>'Order Placed Successfully',
            ]);

          }



        }
        else
        {


            return response()->json([
                'status'=>401,
                'message'=> 'Login to Continue',
            ]);

}


    }



    public function validateOrder(Request $request){


        if(auth('sanctum')->check()){

            $validator = Validator::make($request->all(),[
                'firstname'=>'required|string|max:100',
               'lastname'=>'required|string|max:100',
               'phone'=>'required|numeric',
               'email'=>'required|email|max:100',
               'address'=>'required|string|max:255',
               'city'=>'required|string|max:255',
               'state'=>'required|string|max:255',
               'zipcode'=>'required',
            ]);

          if ( $validator->fails()){

            return response()->json([
                'status'=>422,
                'errors'=> $validator->messages(),
            ]);

          }else {

            return response()->json([
                'status'=>200,
                'message'=>'Form validated Successfully ',
            ]);



        }
    }
        else{
        }


            return response()->json([
                'status'=>401,
                'message'=> 'Login to Continue',
            ]);

} 
}


    

    