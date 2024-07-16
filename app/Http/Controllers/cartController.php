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

        $request->validate([
            'user_id'=>'required',
            'product_id'=>'required',
        ]);
        $product = Product::findOrFail($request->product_id);
        $rowNumber = random_int(100000,100000000000);
        // add the product to cart
        \Cart::session($request->user_id)->add(array(
            'id' => $rowNumber,
            'name' => $product->common_name,
            'price' => $product->price,
            'quantity' => 1,
            'attributes' => array(),
            'associatedModel' => $product,
        ));

        return $this->success(\Cart::session($request->user_id)->getContent(),"Item added to cart successfully");
    }
    public function viewCart(Request $request){
        // dd(Auth::user());
        // dd(session()->all());
        // if (session_status() == PHP_SESSION_ACTIVE) {
        //     return 'Session is active';
        // } else {
        //     return 'Session has not started';
        // }
        return $this->success(\Cart::session($request->user_id)->getContent(),"cart fetched successfully");
    }
}
