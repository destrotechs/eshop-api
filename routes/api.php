<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\productImagesController;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\ratingsController;

use App\Http\Controllers\userController;
use App\Http\Controllers\authController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\subcategoriesController;
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
//products routes
Route::get('/products',[productController::class,'index']);
Route::get('/products/{product}',[productController::class,'product']);
Route::post('/products/create',[productController::class,'store']);
Route::post('/product/images',[productImagesController::class,'store']);
// Route::resource('orders', 'ordersController');
Route::get('/orders',[ordersController::class,'index']);
Route::get('/users',[userController::class,'index']);
Route::post('/users/profile',[profileController::class,'store']);
Route::post('users/address',[profileController::class,'addAddress']);

//category routes
Route::post('categories/create', [categoriesController::class,'store']);
Route::get('categories/all', [categoriesController::class,'index']);
//subcategory routes
Route::post('subcategories/create', [subcategoriesController::class,'store']);
Route::get('subcategories/all', [subcategoriesController::class,'index']);
//rating routes
Route::post('rating/create', [ratingsController::class,'store']);
//auth routes
Route::post('users/register',[authController::class,'store']);
Route::post('users/login',[authController::class,'login']);

