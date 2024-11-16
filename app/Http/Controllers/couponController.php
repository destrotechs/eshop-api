<?php

namespace App\Http\Controllers;
use App\Models\Coupon;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class couponController extends Controller
{
    use HttpResponses;
    public function applyCoupon(Request $request){
        $coupon = $request->coupon;
        $user = $request->user();
        // Validate the coupon code and apply the discount
        $coupon_data = Coupon::where('couponCode', $coupon)->first();
        if ($coupon_data && $coupon_data->discountRate > 0) {
            $cart = new Cart('shopping_cart', null, $user);
            $cart->applyCoupon($coupon, $coupon_data->discountRate);
            return $this->success($cart->getCartSummary(),"Coupon has been applied successfully","Coupon has been applied successfully");
        } else {    
            return $this->error($coupon,"The coupon code is not valid","The coupon code is not valid",300);
        }
        // Return the updated cart summary
    }
}
