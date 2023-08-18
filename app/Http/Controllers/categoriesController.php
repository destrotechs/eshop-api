<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;

class categoriesController extends Controller
{
    use HttpResponses;
    use SoftDeletes;

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
        $category = Category::find($id);
        if($category){
            return $this->success($category,"Category fetched successfully");

        }else{
            return $this->error(null,"Category could not be found",401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::find($id);
        $category->category_code = $request->category_code;
        $category->category_name = $request->category_name;
        $update = $category->update();
        if($category && $update){
            return $this->success($update,"Category updated successfully");
        }else{
            return $this->error(null,"Category could not be");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        $del = $category->delete();
        if($del){
            return $this->success($category,"Category deleted successfully");
        }else{
            return $this->error(null,"Category could not be found",401);
        }
    }
}
