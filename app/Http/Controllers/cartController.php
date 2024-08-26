<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\Cart;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductsResource;
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

        if($user){
            $product = Product::findOrFail($request->product_id);

            if($product){
                $prd = new ProductsResource($product);
                $cart = new Cart('shopping_cart',null,$user);
                $cart->addToCart($prd);
                return $this->success($cart->fetchCart(),"Item added to cart successfully");
            }

        }

        return $this->success($cart->fetchCart(),"Item added to cart successfully");
    }
    public function addToWishList(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
    
        if ($user) {
            $product = Product::findOrFail($request->product_id);
    
            if ($product) {
                // Initialize the wishlist
                $cart = new Cart('wishlist', null, $user);
                
                // Fetch the current wishlist
                $wishlistItems = $cart->fetchCart();
                
                // Check if the product already exists in the wishlist
                $productExists = false;
                foreach ($wishlistItems as $item) {
                    if (isset($item['product_id']) && ($item['product_id'] == $product->id)) {
                        $productExists = true;
                        break;
                    }
                }
    
                if ($productExists) {
                    return $this->success($wishlistItems, "Item is already in the wishlist.");
                }
                
                // Add the product to the wishlist
                $prd = new ProductsResource($product);
                $cart->addToCart($prd);
    
                return $this->success($cart->fetchCart(), "Item added to wishlist successfully");
            }
        }
    
        return $this->success([], "Failed to add item to wishlist");
    }
    

    public function viewCart(Request $request){
        $user = $request->user();
        if(!$user){
            return $this->error(null,'User not authenticated,Token not provided or invalid');
        }
        $cart = new Cart('shopping_cart',null,$user);    
        return $this->success($cart->getCartSummary(),"cart fetched successfully");
    }

    public function viewWishlist(Request $request){
        $user = $request->user();
        if(!$user){
            return $this->error(null,'User not authenticated,Token not provided or invalid');
        }
        $cart = new Cart('wishlist',null,$user);    
        return $this->success($cart->getCartSummary(),"wishlist fetched successfully");
    }

}