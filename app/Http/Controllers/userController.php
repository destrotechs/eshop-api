<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use App\Http\Resources\usertypesResource;
use App\Http\Resources\RoleRightsResource;
use App\Http\Resources\UserRoleResource;
use App\Http\Resources\PaymentModesResource;
use App\Http\Resources\PaymentModesDetailsResource;
use App\Http\Resources\RightsResource;
use App\Traits\HttpResponses;
use App\Models\User;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\RoleRight;
use App\Models\PaymentMode;
use Illuminate\Support\Facades\Auth;
use App\Models\Right;
class userController extends Controller
{
    use HttpResponses;
    public function customers(){
        $users = UserResource::collection(User::whereHas('roles', function ($query) {
            $query->where('role_name','customer');
        })->get());
        if ($users){
            return $this->success($users,'Request was completed successfully');
        }
    }
    public function users(){
        // $this->authorize('can',[Auth::user(),['admin']]);
        $users = UserResource::collection(User::all());
        if ($users){
            return $this->success($users,'Request was completed successfully');
        }
    }
    public function user(Request $request){
        $user = User::find($request->user);
        $user_info = new UserResource($user);
        if($user){
            return $this->success($user_info,null,null);
        }
        return $this->error($request->id,null,"User with this ID could not be found");
    }
    public function rights(){
        $rights = RightsResource::collection(Right::all());
        return $this->success($rights,null,null);
    }

    public function addroles(Request $request){
        $request->validate([
            'role_name'=>'required|unique:roles',
        ]);
        $type = new Role([
            'role_name'=>$request->role_name,
        ]);
        $type->save();
        return $this->success($type,null,"User Role added successfully");
    }
    public function user_roles(){
        $user_roles = UserRoleResource::collection(Role::all());
        return $this->success($user_roles,null,null);
    }

    public function assign_user_roles(Request $request){
        $request->validate([
            'role_id'=>'required',
            'user_id'=>'required',
        ]);

        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);
        
        if($user && $role){

            $user->roles()->attach($role);
        
            return $this->success($user,null,"User Role assigned successfully");
        }

        return $this->error(null,null,"The user or the role supplied is invalid",400);

    
}
public function remove_user_roles(Request $request){
   
    $user = User::findOrFail($request->user_id);
    if($user){
        $user->roles()->detach($request->role_id);

        return $this->success($user,null,'Role removed successfully');
    }
    return $this->error(null,null,"User could not be found");
}

    public function addRights(Request $request){
        $request->validate([
            'right_to'=>'required|unique:rights',
        ]);
        $right = new Right([
            'right_to'=>$request->right_to,
        ]);
        $right->save();
        return $this->success($right,null,'Right created successfully');
    }
    public function rolerights(){
        $rolerights = RoleRightsResource::collection(Role::all());
        return $this->success($rolerights,null,null);
    }


    public function assignrights(Request $request){
        $request->validate([
            'role_id'=>'required',
            'right_id'=>'required',
        ]);

        $role = Role::find($request->role_id);
        $right = Right::find($request->right_id);
        if($role && $right){    
            $role->rights()->attach($right);    
            return $this->success($role,"Rights assigned successfully","Rights assigned successfully");
        }else{
            return $this->error($request,null, "The role supplied is invalid",401);
        }
        
    }

    public function addPaymentMode(Request $request){
        $request->validate([
            'payment_mode_name'=>'required',
            'payment_mode_details'=>'required',            
        ]);
        $mode  =  new PaymentMode([
            'payment_mode_details'=>$request->payment_mode_details,
            'payment_mode_name'=>$request->payment_mode_name,
        ]);

        $mode->save();

        return $this->success($mode,"Payment Mode has been added successfully","Payment Mode has been added successfully",200);
    }
    public function getPaymentModes(){
        $payment_modes = PaymentModesResource::collection(PaymentMode::all());
        return $this->success($payment_modes,"Payment Modes Fetched Successfully");
    }
    public function getPaymentModeDetails(Request $request){
        if($request->payment_mode_id){
            $mode = PaymentMode::findOrFail($request->payment_mode_id);
            $details = new PaymentModesDetailsResource($mode);
            return $this->success($details,null,$mode->payment_mode_name." Details fetched successfully");
        }
        return $this->error(null,null,"The selected payment mode is invalid");
    }
    public function getUserNotifications(Request $request)
    {
        $user = $request->user(); // Get the authenticated user

        // Check if the user is authenticated
        if (!$user) {
            return $this->error(null, null,"User is not authenticated");
        }

        // Fetch only unread notifications (those with null read_at)
        $notifications = $user->notifications()->whereNull('read_at')->get();

        // Return the notifications in the success response
        return $this->success($notifications, null,null);
    }

    public function markAsRead($notificationId)
    {
        $user = auth()->user(); // Get the authenticated user

        // Fetch the notification by its ID
        $notification = $user->notifications()->find($notificationId);

        if ($notification) {
            // Mark the notification as read
            $notification->markAsRead();
            return $this->success( $notification,'Notification marked as read','Notification marked as read', 200);
        }

        return $this->error(null,null,"Notification not found");
    }

}
