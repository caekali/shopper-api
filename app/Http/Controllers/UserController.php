<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
   public function getProfile(Request $request){
    $user = Auth::user();
    return $this->successResponse(new UserResource($user),message:"User Retrived Successfully");

   }
}
