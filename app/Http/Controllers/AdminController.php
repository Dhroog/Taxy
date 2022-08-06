<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "name" => "required|string",
            "phone" => "required|size:10|unique:users",
            "image" => "required",
            "password" => "required|confirmed"
        ]);
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('Images/User'), $imageName);

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->status = true;
        $user->image = $imageName;
        $user->type = 'admin';
        $user->password = Hash::make($request->password);
        $user->save();

        $admin = new Admin();
        $admin->user_id = $user->id;
        $admin->save();
        return $this->returnSuccessMessage();
    }

    public function changeBannedState(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "id" => "required",
            "banned" => "required|boolean",
        ]);
        $user = User::find($request->id);
        if( isset($user) )
        {
            $user->banned = $request->banned;
            $user->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError("user not found");
    }


    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        if( isset($user) )
        {
            $admin = $user->admin;
            $admin->delete();
            $user->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("admin not found");
    }
}
