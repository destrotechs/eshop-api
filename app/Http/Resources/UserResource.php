<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=>$this->name,
            'email'=>$this->email,
            'addresses'=>$this->addresses,
            'orders'=>OrderResource::collection($this->orders),
            'profile'=>$this->profile,
            'roles'=>$this->roles,
            'payment_modes'=>$this->payment_modes,
            // 'rights'=>,
        ];

    }
}
