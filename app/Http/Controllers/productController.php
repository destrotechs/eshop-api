<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductsResource;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\HttpResponses;
use App\Models\subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class productController extends Controller
{
    use HttpResponses;

    public function index(){
        $products = ProductsResource::collection(Product::all());
        if ($products){
            return $this->success($products,'Request was completed successfully');
        }
    }
    public function product(Request $request){
        $product = Product::find($request->product);
        $user = $request->user();
        // return $request->user();

        if($product){
            // return $product->subcategory_id;
            $prd = new ProductsResource($product);
            $similar_products = ProductsResource::collection(
                Product::where('subcategory_id', $product->subcategory_id)
                       ->whereNotIn('id', [$request->product]) // Ensure the current product is not included
                       ->inRandomOrder() // Fetch records in random order
                       ->take(8) // Limit the result to 8 records
                       ->get()
            );
           
            if ($user){
                try{
                    DB::table('last_viewed_products')->insert([
                        'user_id' => $user->id,
                        'product_id' => $product->id,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                }catch(QueryException $e){
                    if ($e->getCode() === '23000') {
                        // Ignore the exception
                    } else {
                        // Optionally rethrow the exception if it's not the one you want to ignore
                        throw $e;
                    }
                };
                
            }
            return $this->success(array("product"=>$prd,"similar_products"=>$similar_products),'Request was completed successfully');
        }
        return $this->error(null,'The requested product could not be found',404);
    }

    public function store(Request $request){
        $request->validate([
            'subcategory_id'=>'required',
            'brand'=>'required',
            'model'=>'required',
            'common_name'=>'required',
            'description'=>'required|string',
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
        $product->options = $request->options;

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
    public function search_products(Request $request){
        $keyword = $request->keyword;
        $products = Product::where('common_name','like','%'.$keyword.'%')->get();
        if(count($products)>0){
            $products = ProductsResource::collection($products);
            return $this->success($products,'Searched products successfully');
        }
        return $this->error($keyword,'Not product could be found',404);
    }
    
    public function search_suggestions(Request $request,$keyword){
        $names = Product::where('common_name','like','%'.$keyword.'%')->pluck('common_name');
        if(count($names)>0){
            return $this->success($names,'Searched products successfully');
        }
        return $this->error($keyword,'Not product could be found',404);
    }
    public function update(Request $request,String $id)
    {
        $product = Product::find($id);
        $update = $product->update([
            'common_name' =>$request->common_name??$product->common_name,
            'subcategory_id'=>$request->subcategory_id??$product->subcategory_id,
            'model'=>$request->model??$product->model,
            'brand'=>$request->brand??$product->brand,
            'sku'=>$request->sku??$product->sku,
            'warrant'=>$request->warrant??$product->warrant,
            'size'=>$request->size??$product->size,
            'dimension'=>$request->dimension??$product->dimension,
            'description'=>$request->description??$product->description,
            'availability'=>$request->availability??$product->availability,
            'product_code'=>$request->product_code??$product->product_code,
            'options'=>$request->options??$product->options,
            'price'=>$request->price??$product->price,
            'bar_code'=>$request->bar_code??$product->bar_code,
        ]);

        if($update){
            return $this->success($product,"Product updated successfully");
        }else{
            return $this->error($product,"Product could not be updated");
        }
    }
    public function destroy(Request $request,$id){
        $product = Product::find($id);
        $del = $product->delete();
        if($del){
            return $this->success($product,"Product deleted successfully");
        }else{
            return $this->error($product,"Product could not be deleted");
        }
    }
}
