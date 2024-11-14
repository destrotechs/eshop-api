<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\RatingsResource;
class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $stock = $this->stock==null?0:$this->stock->sum('quantity_added');
        return [
            'id'=>(string)$this->id,
            'product_code'=>(string)$this->product_code,
            'subcategory'=>$this->subcategory,
            'category'=>$this->subcategory->category,
            'discount'=>$this->discount,
            'description'=>$this->description,
            'price'=>(float)$this->price,
            'size'=>$this->size,
            'brand'=>$this->brand,
            'tags'=>$this->tags,
            'model'=>$this->model,
            'images'=>$this->images,
            'ratings'=> RatingsResource::collection($this->ratings),
            'sku'=>(string)$this->sku,
            'warrant'=>(string)$this->warrant,
            'availability'=>(string)$this->availability,
            'dimension'=>(string)$this->dimension,
            'bar_code'=>(string)$this->bar_code,
            'name'=>(string)$this->common_name,
            'stock'=>$stock,
            'options'=>explode(',',$this->options),

        ];
    }
}
