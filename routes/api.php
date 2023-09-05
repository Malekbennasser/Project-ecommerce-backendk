<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
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




Route::middleware('auth:sanctum','isAPIAdmin')->group(function () {
    Route::get('/chekingAuthenticated',function(){
        return response()->json(['message'=>'You are in dashbord','status'=>200],200);
    });
   Route::get('view-category',[CategoryController::class,'index']);
   Route::get('/edit-category/{id}',[CategoryController::class,'edit']);
   Route::put('/update-category/{id}',[CategoryController::class,'update']);
   Route::delete('delete-category/{id}',[CategoryController::class,'destroy']);
   Route::post('category', [CategoryController::class,'store']);
   Route::get('all-category',[CategoryController::class,'allcategory']);

   //Products

   Route::post('sotre-product',[ProductController::class,'store']);
});


Route::middleware('auth:sanctum' )->group(function () {
  
    
Route::post('logout',[AuthController::class,'logout']);

});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});