<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use App\Traits\HttpResponses;
use App\Models\subcategory;
use Illuminate\Support\Facades\DB;

class dashboardController extends Controller
{
    use HttpResponses;

    public function index(Request $request){
        $user = $request->user();
        $last_viewed = DB::table('last_viewed_products')
            ->where('user_id', $user->id)
            ->pluck('product_id');
            $last_viewed_products = Product::whereIn('id', $last_viewed)->get();
            $viewed_products = ProductsResource::collection($last_viewed_products);
        return $this->success(array('last_viewed_products' =>$viewed_products));
    }
}
