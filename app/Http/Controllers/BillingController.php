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
class BillingController extends Controller
{
   //
}
