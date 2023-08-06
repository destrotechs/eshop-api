<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class subcategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'subcategory_code'=>$this->subcategory_code,
            'subcategory_name'=>$this->subcategory_name,
            'category'=>$this->category,
        ];
    }
}
