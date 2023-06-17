<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\userController;
use App\Http\Controllers\authController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    // Your authenticated API routes here
});
Route::get('/products',[productController::class,'index']);
Route::get('/products/{product}',[productController::class,'product']);
// Route::resource('orders', 'ordersController');
Route::get('/orders',[ordersController::class,'index']);
Route::get('/users',[userController::class,'index']);
Route::post('/users/profile',[profileController::class,'store']);
Route::post('users/address',[profileController::class,'addAddress']);

//auth routes
Route::post('users/register',[authController::class,'store']);
Route::post('users/login',[authController::class,'login']);

