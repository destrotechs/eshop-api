<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\productController;
use App\Http\Controllers\productImagesController;
use App\Http\Controllers\ordersController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\ratingsController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\userController;
use App\Http\Controllers\authController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\subcategoriesController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\cartController;

use Illuminate\Support\Facades\Log;
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
    $data = ['user'=>Auth::user()];
    // $token = $request->bearerToken();

    // Log::info('Received token:', ['token' => $token]);
    return response()->json($data);
});


//auth routes
Route::post('users/register',[authController::class,'store']);
Route::post('users/login',[authController::class,'login']);

// Route::resource('orders', 'ordersController');


//cart
Route::post('/shopping/cart', [cartController::class,'addToCart']);

Route::get('/shopping/cart/{user_id}', [cartController::class,'viewCart']);
Route::post('/orders/add/', [ordersController::class,'store']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    //category routes
    Route::post('categories/create', [categoriesController::class,'store']);
    Route::get('categories/all', [categoriesController::class,'index']);
    Route::get('category/{id}', [categoriesController::class,'show']);
    Route::put('/category/{id}',[categoriesController::class,'update']);
    Route::delete('/category/{id}',[categoriesController::class,'destroy']);

    //subcategory routes
    Route::post('subcategories/create', [subcategoriesController::class,'store']);
    Route::get('subcategories/all', [subcategoriesController::class,'index']);
    Route::get('subcategory/{id}', [subcategoriesController::class,'show']);
    Route::put('/subcategory/{id}',[subcategoriesController::class,'update']);
    Route::delete('subcategory/{id}', [subcategoriesController::class,'destroy']);

    //rating routes
    Route::post('rating/create', [ratingsController::class,'store']);
    
    //payment modes
    // Route::get('/payments/modes', [userController::class,'getPaymentModes']);
    Route::post('/payments/modes', [userController::class,'addPaymentMode']);
    Route::get('/payments/modes/details/{payment_mode_id}', [userController::class,'getPaymentModeDetails']);

    Route::get('/payments/modes', [userController::class,'getPaymentModes']);
    Route::get('/orders',[ordersController::class,'index']);
    Route::get('/customers',[userController::class,'customers']);
    Route::get('/users',[userController::class,'users']);
    Route::get('/user/{user}',[userController::class,'user']);
    Route::get('/rights/all',[userController::class,'rights']);
    Route::get('/users/roles',[userController::class,'user_roles']);
    Route::get('/role/rights',[userController::class,'rolerights']);
    Route::post('/roles',[userController::class,'addroles']);
    Route::post('/users/profile',[profileController::class,'store']);
    Route::put('/users/profile',[profileController::class,'updateProfile']);
    Route::post('users/address',[profileController::class,'addAddress']);
    Route::post('user/assign/roles',[userController::class,'assign_user_roles']);
    Route::post('role/assign/rights',[userController::class,'assignrights']);
    Route::post('/rights',[userController::class,'addRights']);
    Route::post('/roles/remove',[userController::class,'remove_user_roles']);
    //products routes
    Route::get('/products',[productController::class,'index']);
    Route::get('/products/{product}',[productController::class,'product']);
    Route::post('/products/create',[productController::class,'store']);
    Route::post('/product/images',[productImagesController::class,'store']);
    Route::put('/product/{id}',[productController::class,'update']);
    Route::delete('/product/{id}',[productController::class,'destroy']);
    //stocks
    Route::post('stocks/create', [StockController::class,'store']);
    Route::get('stocks/fetch/{product_id}', [StockController::class,'ProductStock']);

    Route::get('/orders',[ordersController::class,'index']);
    Route::get('/customers',[userController::class,'customers']);
    Route::get('/users',[userController::class,'users']);
    Route::get('/user/{user}',[userController::class,'user']);
    Route::get('/rights/all',[userController::class,'rights']);
    Route::get('/users/roles',[userController::class,'user_roles']);
    Route::get('/role/rights',[userController::class,'rolerights']);
    Route::post('/roles',[userController::class,'addroles']);
    Route::post('/users/profile',[profileController::class,'store']);
    Route::put('/users/profile',[profileController::class,'updateProfile']);
    Route::post('users/address',[profileController::class,'addAddress']);
    Route::post('user/assign/roles',[userController::class,'assign_user_roles']);
    Route::post('role/assign/rights',[userController::class,'assignrights']);
    Route::post('/rights',[userController::class,'addRights']);
    Route::post('/roles/remove',[userController::class,'remove_user_roles']);                                                                                                           

});
