<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_number'=>$this->order_number,
            'owner'=>$this->user,
            'items'=>$this->items,
            'shipping_address'=>$this->address,
            'order_date'=>$this->date_created,
        ];
    }
}
