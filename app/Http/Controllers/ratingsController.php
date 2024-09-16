<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Rating;
use App\Models\Product;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class ratingsController extends Controller
{
    use HttpResponses;

    public function store(Request $request){
        $request->validate([
            'product_id'=>'required',
            'rating'=>'required',
        ]);

        $user = $request->user();
        $product = Product::find($request->product_id);

        if($user && $product){
            $rating = new Rating();
            $rating->star_rate = $request->rating;
            $rating->user_id = $user->id;
            $rating->review = $request->review;

            $product->ratings()->save($rating);

            return $this->success($rating,"Thank you for rating this product");

        }else{
            return $this->error(null,"Product rating could not be added",401);
        }
    }
}
