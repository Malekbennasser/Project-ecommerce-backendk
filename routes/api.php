<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::get('getproduct',[FrontendController::class,'getProduct']);
Route::get('getcategory',[FrontendController::class,'category']);
Route::get('fetchproducts/{slug}',[FrontendController::class,'product']);
Route::get('viewproductdetail/{category_slug}/{product_slug}',[FrontendController::class,'viewproduct']);
Route::post('add-to-cart',[CartController::class, 'addtocart']);
Route::get('cart', [CartController::class, 'viewcart']);
Route::put('cart-updatequantity/{cart_id}/{scope}',[CartController::class,'updatequantity']);
Route::delete('delete-cartitem/{cart_id}',[CartController::class,'deleteCartitem']);


Route::post("validate-order",[CheckoutController::class,'validateOrder']);
Route::post('place-order',[CheckoutController::class, 'placeorder']);


//orders

Route::get("orders",[OrderController::class, 'index']);
Route::post("test",[PaymentController::class, 'index']);








Route::middleware('auth:sanctum','isAPIAdmin')->group(function () {
    Route::get('/chekingAuthenticated',function(){
        return response()->json(['message'=>'You are in dashbord','status'=>200]);
    });
   Route::get('view-category',[CategoryController::class,'index']);
   Route::get('/edit-category/{id}',[CategoryController::class,'edit']);
   Route::put('/update-category/{id}',[CategoryController::class,'update']);
   Route::delete('delete-category/{id}',[CategoryController::class,'destroy']);
   Route::post('category', [CategoryController::class,'store']);
   Route::get('all-category',[CategoryController::class,'allcategory']);

   //Products

   Route::post('store-product',[ProductController::class,'store']);
   Route::get('view-product',[ProductController::class,'index']);
   Route::get('/edit-product/{id}',[ProductController::class,'edit']);
   Route::post('/update-product/{id}',[ProductController::class,'update']);
   Route::delete('delete-product/{id}',[ProductController::class,'destroy']);


});


Route::middleware('auth:sanctum' )->group(function () {
  
     Route::post('logout',[AuthController::class,'logout']);


});
//  Route::middleware('auth:sanctum')->post('logout', function (Request $request) {
//     $request->user()->currentAccessToken()->delete();
  
//     return response()->json(['message' => 'Successfully logged out']);
// });


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
