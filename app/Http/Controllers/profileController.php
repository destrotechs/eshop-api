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
            'user_id'=>'required:unique:profiles',
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
    public function updateProfile(Request $request){
        if($request->isJson()){
            $profile = Profile::findOrFail($request->id);
            $profile->phone_number=$request->phoneNumber??$profile->phone_number;
            $profile->card_number=$request->cardNumber??$profile->card_number;
            $profile->cvv=$request->cvv??$profile->cvv;
            $profile->locale=$request->locale??$profile->locale;

            $profile->update();

            return $this->success($profile,"Profile details updated successfully");
        }
        return $this->error(null,"Only json data is accepted",401);
    }

    public function addAddress(Request $request){
        $request->validate([
            'address'=>'required|string',
        ]);

        $user = $request->user();

        $address = new Address(
            ['shipping_address'=>$request->address],
        );

        $user->addresses()->save($address);

        return $this->success($address,'Address added successfully');
    }
    public function removeAddress(Request $request, $address){
        $user = $request->user();
        if($user){
            $address = Address::find($address);
            if($address){
                $address->delete();
                return $this->success($address,'Address removed successfully');
            }
        }else{
            return $this->error(null,"User not found",404);
        }
        

    }


}
