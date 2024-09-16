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
use App\Http\Controllers\dashboardController;

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

// routes without need  for authentication
Route::get('categories/all', [categoriesController::class,'index']);
Route::get('/products',[productController::class,'index']);
Route::post('/products/search',[productController::class,'search_products']);
Route::get('/search/suggestions/{keyword}',[productController::class,'search_suggestions']);
Route::get('/products/{product}',[productController::class,'product']);
Route::get('subcategories/all', [subcategoriesController::class,'index']);
Route::get('subcategory/{id}', [subcategoriesController::class,'show']);

//cart

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/shopping/cart', [cartController::class,'addToCart']);
    Route::put('/shopping/quantity', [cartController::class,'updateQuantity']);
    Route::post('/shopping/wishlist', [cartController::class,'addToWishList']);
    Route::get('/user/{user}',[userController::class,'user']);
    Route::get('/dashboard/index',[dashboardController::class,'index']);
    Route::get('/shopping/cart/', [cartController::class,'viewCart']);
    Route::post('/shopping/cart/remove', [cartController::class,'removeFromCart']);
    Route::get('/shopping/wishlist/', [cartController::class,'viewWishlist']);
    Route::post('/shopping/wishlist/remove', [cartController::class,'removeFromWishlist']);
    Route::post('/orders/add/', [ordersController::class,'store']);
    Route::post('/users/profile',[profileController::class,'store']);
    Route::put('/users/profile',[profileController::class,'updateProfile']);
    Route::post('users/address',[profileController::class,'addAddress']);
    Route::delete('users/address/{address}',[profileController::class,'removeAddress']);
    Route::get('/payments/modes/details/{payment_mode_id}', [userController::class,'getPaymentModeDetails']);

    Route::get('/payments/modes', [userController::class,'getPaymentModes']);
    
});
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    //category routes
    Route::post('categories/create', [categoriesController::class,'store']);
   
    Route::get('category/{id}', [categoriesController::class,'show']);
    Route::put('/category/{id}',[categoriesController::class,'update']);
    Route::delete('/category/{id}',[categoriesController::class,'destroy']);

    //subcategory routes
    Route::post('subcategories/create', [subcategoriesController::class,'store']);
    
    Route::put('/subcategory/{id}',[subcategoriesController::class,'update']);
    Route::delete('subcategory/{id}', [subcategoriesController::class,'destroy']);

    //rating routes
    Route::post('review/add', [ratingsController::class,'store']);
    
    //payment modes
    // Route::get('/payments/modes', [userController::class,'getPaymentModes']);
    Route::post('/payments/modes', [userController::class,'addPaymentMode']);
    
    Route::get('/orders',[ordersController::class,'index']);
    Route::get('/customers',[userController::class,'customers']);
    Route::get('/users',[userController::class,'users']);
    Route::get('/user/{user}',[userController::class,'user']);
    Route::get('/rights/all',[userController::class,'rights']);
    Route::get('/users/roles',[userController::class,'user_roles']);
    Route::get('/role/rights',[userController::class,'rolerights']);
    Route::post('/roles',[userController::class,'addroles']);
    
    Route::post('user/assign/roles',[userController::class,'assign_user_roles']);
    Route::post('role/assign/rights',[userController::class,'assignrights']);
    Route::post('/rights',[userController::class,'addRights']);
    Route::post('/roles/remove',[userController::class,'remove_user_roles']);
    //products routes
    
    Route::post('/products/create',[productController::class,'store']);
    Route::post('/product/images',[productImagesController::class,'store']);
    Route::put('/product/{id}',[productController::class,'update']);
    Route::delete('/product/{id}',[productController::class,'destroy']);
    //stocks
    Route::post('stocks/create', [StockController::class,'store']);
    Route::get('stocks/fetch/{product_id}', [StockController::class,'ProductStock']);

    Route::get('/orders',[ordersController::class,'index']);
    Route::get('/order/{id}',[ordersController::class,'show']);
    Route::get('/customers',[userController::class,'customers']);
    Route::get('/users',[userController::class,'users']);
   
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
Route::get('/debug-session', function () {
    return session()->all();
});