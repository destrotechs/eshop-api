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
            'password'=>'required|min:6',
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
}
