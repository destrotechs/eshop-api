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
            'category_code' => 'required|unique:categories',
            'category_name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image if provided
        ]);

        $category = new Category();
        $category->category_code = $request->category_code;
        $category->category_name = $request->category_name;

        // Check if an image is uploaded
        if ($request->hasFile('image')) {
            // Store the image in the 'public' storage folder
            $imagePath = $request->file('image')->store('images', 'public');
            $category->image_path = $imagePath; // Save the image path to the database
        }

        if ($category->save()) {
            return $this->success($category, "Category added successfully");
        } else {
            return $this->error(null, "Category could not be created successfully", 401);
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
        if ($request->hasFile('image')) {
            // Store the image in the 'public' storage folder
            $imagePath = $request->file('image')->store('images', 'public');
            $category->image_path = $imagePath; // Save the image path to the database
        }
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
