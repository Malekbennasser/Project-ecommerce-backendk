<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class PaymentController extends Controller
{
  public function index(Request $request){

  

    $validator = Validator::make($request->all(), [
      
        'number' => 'required',
        'exp_month' => 'required',
        'exp_year' => 'required',
        'cvc' => 'required',
        'totalCartPrice'=>'required',
        'orderId'=>'required'
    ]);
    
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 400);
    }
  

    try {
        
        $stripe = new \Stripe\StripeClient(
            // Configuration de la clé secrète Stripe
            env('STRIPE_SECRET') 
        );
       
        $totalCartPrice = $request->input('totalCartPrice') * 100;
        $orderId = $request->input('orderId');
        $paymentMethodId = 'pm_card_visa'; 

        $order = Order::latest()->first();
        $customerName = $order->firstname;
        $customerEmail = $order->email;

        // Créer un client Stripe avec les informations du client
        $customer =$stripe->customers->create([
            'name'=> $customerName,
            'email'=> $customerEmail
           
          ]);
        $customerId = $customer->id;
      

  
       
       
   
$paymentIntent =$stripe->paymentIntents->create([
             
            'amount' =>  $totalCartPrice ,
            'currency' => 'eur',
            'payment_method' =>  $paymentMethodId,
            'customer' => $customerId,
            'confirm' => true,
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ],
            
          ]);
          $paymentId = $paymentIntent->id;

        //   $stripe = new \Stripe\StripeClient((env('STRIPE_SECRET')));
       
        /*  dd($test);  */
        $order->payment_id = $paymentId;
            $order->save();


return response()->json([ "paymentIntent"=>$paymentIntent,"order"=>$order, "customer"=>$customer]);
      
} catch (\Stripe\Exception\CardException $e) {
    // Gérer les erreurs liées à la carte
    return response()->json(['response' => $e->getMessage()], 400);
} catch (\Stripe\Exception\InvalidRequestException $e) {
    // Gérer les erreurs liées à une demande invalide
    return response()->json(['response' => $e->getMessage()], 400);
    } catch (Exception $ex) {
        Log::error($ex);

        return response()->json(['response' => 'Error'], 500); // Renvoi d'une réponse d'erreur en cas d'exception
    }
    }

}



