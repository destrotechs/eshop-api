<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\Product;

use App\Traits\HttpResponses;

class StockController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stocks = Stock::all();
        if($stocks){
            return $this->success($stocks,"Stocks fetched successfully");
        }else{
            return $this->error($stocks,"Stocks not found");
        }

    }
    public function ProductStock(Request $request,$product_id)
    {
        $stocks = Stock::with('product')->where('product_id','=',$product_id)->get();
        if($stocks){
            return $this->success($stocks,"Stocks fetched successfully");
        }else{
            return $this->error($stocks,"Stocks not found");
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'quantity_added'=>'required|numeric',
            'unit_of_measure'=>'required',
            'product_id'=>'required',
        ]);
        $stock =  new Stock();
        $stock->quantity_added = $request->quantity_added;
        $stock->unit_of_measure = $request->unit_of_measure;
        $stock->date_altered = date('Y-m-d');
        $product = Product::find($request->product_id);

        $add = $product->stock()->save($stock);

        if($add){
            return $this->success($stock,"Product stock added successfully");
        }else{
            return $this->error($stock,"Stock record could not be added");
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
