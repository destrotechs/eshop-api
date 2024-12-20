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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\couponController;
use App\Http\Controllers\mpesaController;

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
Route::get('/products/featured/all', [productController::class,'get_featured_products']);

//cart

Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('/shopping/cart', [cartController::class,'addToCart']);
    Route::put('/shopping/quantity', [cartController::class,'updateQuantity']);
    Route::post('/shopping/wishlist', [cartController::class,'addToWishList']);
    Route::get('/user/{user}',[userController::class,'user']);
    // Route::get('/dashboard/index',[dashboardController::class,'index']);
    Route::get('/shopping/cart/', [cartController::class,'viewCart']);
    Route::post('/shopping/cart/remove', [cartController::class,'removeFromCart']);
    Route::get('/shopping/wishlist/', [cartController::class,'viewWishlist']);
    Route::post('/shopping/wishlist/remove', [cartController::class,'removeFromWishlist']);
    Route::post('/orders/add/', [ordersController::class,'store']);
    Route::post('/users/profile',[profileController::class,'store']);
    Route::put('/users/profile',[profileController::class,'updateProfile']);
    Route::post('users/address',[profileController::class,'addAddress']);
    Route::delete('users/address/{address}',[profileController::class,'removeAddress']);
    Route::get('/paymentmodes/details/{payment_mode_id}', [userController::class,'getPaymentModeDetails']);
  

    Route::get('/payments/modes', [userController::class,'getPaymentModes']);
    Route::get('/payments/all', [PaymentController::class,'getAllPayments']);
    Route::get('/orders',[ordersController::class,'index']);
    Route::get('/orders/{id}',[ordersController::class,'show']);
    Route::get('/user/{user}',[userController::class,'user']);
    Route::get('/notifications',[userController::class,'getUserNotifications']);
    Route::post('/users/profile',[profileController::class,'store']);
    Route::patch('/notifications/{notificationId}/read',[userController::class,'markAsRead']);
    Route::put('/users/profile',[profileController::class,'updateProfile']);
    Route::post('users/address',[profileController::class,'addAddress']);
    Route::post('review/add', [ratingsController::class,'store']);
    Route::post('coupon/apply', [couponController::class,'applyCoupon']);
    Route::post('/pay-via-mpesa', [PaymentController::class, 'mpesaPayment']);
    Route::get('/products/{id}/user-review',[productController::class,'get_product_rating']);
    Route::get('/register_urls',[PaymentController::class,'register_callback_urls']);
    Route::put('/order/update/{orderId}',[ordersController::class,'update']);


    
});
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    //category routes
    Route::get('dashboard/data', [dashboardController::class,'getDashboardData']);
    Route::get('dashboard/topselling/{month}', [dashboardController::class,'getTopSellingProducts']);
    Route::post('categories/create', [categoriesController::class,'store']);
   
    Route::get('category/{id}', [categoriesController::class,'show']);
    Route::put('/category/{id}',[categoriesController::class,'update']);
    Route::delete('/category/{id}',[categoriesController::class,'destroy']);

    //subcategory routes
    Route::post('subcategories/create', [subcategoriesController::class,'store']);
    
    Route::put('/subcategory/{id}',[subcategoriesController::class,'update']);
    
    Route::delete('subcategory/{id}', [subcategoriesController::class,'destroy']);

    //rating routes
    
    //payment modes
    // Route::get('/payments/modes', [userController::class,'getPaymentModes']);
    Route::post('/payments/modes', [userController::class,'addPaymentMode']);
    
    Route::get('/customers',[userController::class,'customers']);
    Route::get('/users',[userController::class,'users']);
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

    
    Route::get('/customers',[userController::class,'customers']);
    Route::get('/users',[userController::class,'users']);
   
    Route::get('/rights/all',[userController::class,'rights']);
    Route::get('/users/roles',[userController::class,'user_roles']);
    Route::get('/role/rights',[userController::class,'rolerights']);
    Route::post('/roles',[userController::class,'addroles']);
   
    Route::post('user/assign/roles',[userController::class,'assign_user_roles']);
    Route::post('role/assign/rights',[userController::class,'assignrights']);
    Route::post('/rights',[userController::class,'addRights']);
    Route::post('/roles/remove',[userController::class,'remove_user_roles']);                                                                                                           

});
Route::get('/debug-session', function () {
    return session()->all();
});
Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/callback', [PaymentController::class, 'handleCallback']);
Route::post('/validation', [mpesaController::class, 'validation']);
Route::post('/confirmation', [mpesaController::class, 'confirmation']);


