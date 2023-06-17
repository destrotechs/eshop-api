<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class authController extends Controller
{
    use HttpResponses;

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'password'=>'required|min:6|confirmed',
            'email'=>'required',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->password = Hash::make($request->get('password'));
        $user->email = $request->get('email');

        $user->save();

        $token = $user->createToken('token-name')->plainTextToken;

        if($user){
            return $this->success($token);
        }


    }
    public function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        $user = User::where('email',$request->email)->first();
        if(count($user)>0){
            if(Hash::make($request->get('password'))==$user->passwords){
                $token = $user->createToken('token-name')->plainTextToken;
                return $this->success($token);
            }else{
                return $this->error(null,'Wrong Credentials',401);
            }
        }else{
            return $this->error(null,'User not found',401);
        }
    }
}
