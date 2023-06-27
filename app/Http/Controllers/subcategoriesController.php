<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subcategory;
use App\Models\Category;
use App\Traits\HttpResponses;

class subcategoriesController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = subcategory::all();
        return $this->success($subcategories,'subcategories fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subcategory_code'=>'required|unique:subcategories',
            'subcategory_name'=>'required',
            'category_id'=>'required',
        ]);

        $subcategory = new subcategory();
        $subcategory->subcategory_code = $request->subcategory_code;
        $subcategory->subcategory_name = $request->subcategory_name;

        $category = Category::find($request->category_id);

        if($category->subcategories()->save($subcategory)){
            return $this->success($subcategory,'success');
        }else{
            return $this->error($request->all(),'There was an error creating the subcategory',401);
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
