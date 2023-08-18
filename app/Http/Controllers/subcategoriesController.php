<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\subcategory;
use App\Models\Category;
use App\Traits\HttpResponses;
use App\Http\Resources\subcategoryResource;
use Illuminate\Database\Eloquent\SoftDeletes;

class subcategoriesController extends Controller
{
    use HttpResponses;
    use SoftDeletes;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subcategories = subcategoryResource::collection(subcategory::all());
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
            return $this->success($subcategory,'Subcategory added successfully');
        }else{
            return $this->error($request->all(),'There was an error creating the subcategory',401);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subc = subcategory::find($id);
        if($subc){
            $record =  new subcategoryResource($subc);
            return $this->success($record,"Subcategory fetched successfully");
        }else{
            return $this->error(null,'The subcategory could not be found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'subcategory_code'=>'required|unique:subcategories',
            'subcategory_name'=>'required',
        ]);
        $subc = subcategory::find($id);

        $updated = $subc->update(['subcategory_code'=>$request->subcategory_code??$subc->subcategory_code, 'subcategory_name'=>$request->subcategory_name??$subc->subcategory_name,'category_id'=>$request->category_id??$subc->category_id]);
        if($updated){
            return $this->success($updated,"Subcategory updated successfully");
        }else{
            return $this->error(null,"There was a problem updating the subcategory");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subc = subcategory::find($id);
        $del = $subc->delete();
        if($del){
            return $this->success($del,"Subcategory deleted successfully");
        }else{
            return $this->error(null,"There was a problem deleting the subcategory");
        }
    }
}
