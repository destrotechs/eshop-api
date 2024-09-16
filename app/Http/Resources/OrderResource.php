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
            'id'=>$this->id,
            'order_number'=>$this->order_number,
            'total_cost'=>$this->total_cost,
            'owner'=>$this->user,
            'items'=>$this->items,
            'shipping_address'=>$this->address,
            'status'=>$this->status,
            'order_date'=>$this->created_at,
            'payment'=>$this->payment,
            'payment_mode'=>$this->payment_mode_id,
        ];
    }
}
