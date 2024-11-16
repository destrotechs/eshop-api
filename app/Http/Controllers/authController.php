<?php

namespace App\Http\Controllers;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\usertype;
use App\Models\UsertypeList;
use App\Models\Role;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
class authController extends Controller
{
    use HttpResponses;

    public function store(Request $request){
        $request->validate([
            'name'=>'required',
            'password'=>'required|min:6|confirmed',
            'email'=>'required|unique:users',
        ]);

        $user = new User();
        $user->name = $request->get('name');
        $user->password = Hash::make($request->get('password'));
        $user->email = $request->get('email');

         
        $role = $request->role_id?Role::find($request->role_id):Role::where('role_name','customer')->first();
        
        $user->save();

        $user->roles()->attach($role);
        $token = $user->createToken('token-name')->plainTextToken;

        if($user){
            return $this->success($token, "User created successfully","User created successfully");
        }else{
            return $this->error($user,"User could not be created","User could not be created");
        }       

    }
    public function login(Request $request){
        $request->validate([
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed, redirect the user to a desired location
            $user = User::where('email',$request->email)->first();
            // $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('token-name')->plainTextToken;
            return $this->success(['accessToken'=>$token,'user'=>$user]);
        }else{
            return $this->error(null,'Wrong Credentials','Wrong Credentials',201);
        }

    }
    public function logout(Request $request){
        $user = Auth::user();
        $user->tokens()->delete();
        return $this->success(null,'Logout successful','Wrong Credentials');

    }
}
