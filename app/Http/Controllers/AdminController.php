<?php

namespace App\Http\Controllers;

use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Admin;
class AdminController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|string",
            "status" => "required",
            "banned" => "required",
            "image" => "required",
            "password" => "required",
        ]);
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->status = $request->status;
        $admin->banned = $request->banned;
        $admin->image = $request->image;
        $admin->password = $request->password;
        $admin->save();
        return $this->returnSuccessMessage();
    }

    public function changeBannedState(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            "id" => "required",
            "banned" => "required",
        ]);
        $admin=Admin::find($request->id);
        if( isset($admin) )
        {
            return $this->returnSuccessMessage();
        }else return $this->returnError("admin not found");
    }
    // public function edit(Request $request): \Illuminate\Http\JsonResponse
    // {
    //     $request->validate([
    //     ]);
    //     $admin=Admin::find($request->id);
    //     if( isset($admin) )
    //     {
    //         return $this->returnSuccessMessage();
    //     }else return $this->returnError("admin not found");
    // }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $admin = Admin::find($id);
        if( isset($admin) )
        {
            $admin->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("admin not found");
    }
}
