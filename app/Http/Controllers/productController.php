<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\HttpResponses;

class productController extends Controller
{
    use HttpResponses;

    public function index(){
        $products = ProductsResource::collection(Product::all());
        if ($products){
            return $this->success($products,'Request was completed successfully');
        }
    }
    public function product(Product $product){
        return $product;
    }
}
