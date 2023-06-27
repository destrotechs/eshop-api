<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class categoriesController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return $this->success($categories,'Categories fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_code' =>'required|unique:categories',
            'category_name' =>'required',
        ]);

        $category = new Category();
        $category->category_code = $request->category_code;
        $category->category_name = $request->category_name;

        if($category->save()){
            return $this->success($category,"category added successfully");

        }else{
            return $this->error(null,"category could not be created successfully",401);

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
