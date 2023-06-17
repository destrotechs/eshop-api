<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Traits\HttpResponses;
use App\Models\User;
class userController extends Controller
{
    use HttpResponses;
    public function index(){
        $users = UserResource::collection(User::all());
        if ($users){
            return $this->success($users,'Request was completed successfully');
        }
    }

}
