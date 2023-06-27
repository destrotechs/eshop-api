<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\HttpResponses;
use App\Models\subcategory;

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
        if($product){
            return $this->success($product,'Request was completed successfully');
        }
        return $this->error(null,'The requested product could not be found',404);
    }

    public function store(Request $request){
        $request->validate([
            'subcategory_id'=>'required',
            'brand'=>'required',
            'model'=>'required',
            'common_name'=>'required',
            'description'=>'required',
            'product_code'=>'required|unique:products',
            'price'=>'required',
        ]);
        $product = new Product();
        $product->subcategory_id = $request->subcategory_id;
        $product->brand = $request->brand;
        $product->model = $request->model;
        $product->common_name = $request->common_name;
        $product->sku = $request->sku;
        $product->warrant = $request->warrant;
        $product->size = $request->size;
        $product->dimension = $request->dimension;
        $product->availability = $request->availability;
        $product->bar_code = $request->bar_code;
        $product->description = $request->description;
        $product->product_code = $request->product_code;
        $product->price = $request->price;

        $subcategory = subcategory::find($request->subcategory_id);
        $added = $subcategory->products()->save($product);

        if($added){
            return $this->success($product,'Product added successfully');
        }else{
            return $this->error($request->all(),'There was a problem adding the product',401);
        }


    }
    public function search_products(Request $request,$keyword){
        $products = Product::where(['common_name','like','%'.$keyword.'%'])->get();
        if(count($products)>0){
            return $this->success($products,'Searched products successfully');
        }
        return $this->error($keyword,'Not product could be found',404);
    }
}
