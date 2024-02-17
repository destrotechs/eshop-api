<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Profile;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Traits\HttpResponses;


class profileController extends Controller
{
    use HttpResponses;
    public function store(Request $request){
        $request->validate([
            'phone_number'=>'required|min:10|max:10|unique:profiles',
            'preferred_payment'=>'required',
            'card_number'=>'unique:profiles',
        ]);

        $user = User::find($request->user_id);
        $profile = new Profile([
            'phone_number'=>$request->phone_number,
            'card_number'=>$request->card_number,
            'preferred_payment'=>$request->preferred_payment,
        ]);
        // 1|TuhOK49ZRtLoj3YQirOIu0HxxqcPD2lo7RykEMJu
        $user->profile()->save($profile);

       

        return $this->success($user);
    }

    public function addAddress(Request $request){
        $request->validate([
            'shipping_address'=>'required|string',
        ]);

        $user = User::find($request->user_id);

        $address = new Address(
            ['shipping_address'=>$request->shipping_address],
        );

        $user->addresses()->save($address);

        return $this->success($address,'Address added successfully');
    }
}
