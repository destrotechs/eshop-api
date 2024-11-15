<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>(string)$this->id,
            'order_id'=>$this->order->order_number,
            'amount'=>$this->amount,
            'currency'=>$this->currency,
            'payment_mode'=>$this->payment_mode->payment_mode_name,
            'payment_id'=>$this->payment_id,
            'paid_on'=>$this->paid_on,
            'payment_details'=>$this->payment_details,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
            
            
        ];
    }
}
