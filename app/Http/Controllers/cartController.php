<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
class cartController extends Controller
{
    use HttpResponses;

    public function index(){
        
    }
    public function addToCart(Request $request){
        $user = $request->user();
        $request->validate([
            'product_id'=>'required',
        ]);
        $product = Product::findOrFail($request->product_id);

        \Cart::add(array(
            'id' => $product->id,
            'name' => $product->common_name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product,
        ));

        return $this->success(\Cart::getContent(),"Item added to cart successfully");
    }
    public function viewCart(Request $request){
        $user = $request->user();
        // \Cart::clear();
        // \Cart::session($user->id)->clear();
        $cart = \Cart::getContent();
        if(!$user){
            return $this->error(null,'User not authenticated','Token not provided or invalid');
        }
        return $this->success($cart,"cart fetched successfully");
    }

}
