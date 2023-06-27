<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Models\Product;
use App\Models\ProductImage;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Storage;

class productImagesController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id'=>'required',
            'img'=>'required',
        ]);
        $product = Product::find($request->product_id);

        //upload image
        if($product){
            // return $request->file('img');
            $path = Storage::putFile(storage_path('/product_images'),$request->file('img'));
            //end upload
            $img = new ProductImage();
            $img->img_url = $path;//should be path where image is stored


            $added = $product->images()->save($img);

            if($added) {
                return $this->success($img,'Image was added successfully');
            }else{
                return $this->error($request->all(),'There was a problem adding the image',401);
            }
        }else{
            return $this->error(null,'The selected product could not be found',404);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
