<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Social;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): JsonResponse
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

    public function changeBannedState(Request $request): JsonResponse
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

    public function delete($id): JsonResponse
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

    public function ChangePasswordFromAdmin(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|int',
            "password" => "required|confirmed"
        ]);

        $user = User::find($request->user_id);
        if(isset($user)){
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError('user not found');
    }

    public function InsertSocialMedia(Request $request){
        $request->validate([
            'name' => 'required|string',
            'link' => 'required'
        ]);

        $social = new Social();
        $social->name = $request->name;
        $social->link = $request->link;
        $social->save();
        return $this->returnSuccessMessage();
    }

    public function EditSocialMedia(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|int',
            'name' => 'required|string',
            'link' => 'required'
        ]);


        $social = Social::find($request->id);
        if(isset($social)){
            $social->name = $request->name;
            $social->link = $request->link;
            $social->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError('not found');

    }
    public function GetSocialMedia(): JsonResponse
    {
        $social = Social::all();
        if(isset($social)){
            return $this->returnData('Social Media',$social);
        }else return $this->returnError('not found');
    }

    public function GetNotes(): JsonResponse
    {
        $notes = Note::all();
        if(isset($notes)){
            return $this->returnData('notes',$notes);
        }else return $this->returnError();
    }

    public function GetNotesForUser($id): JsonResponse
    {
        $user = User::find($id);
        if(isset($user)){
            $notes = $user->notes;
            if(isset($notes)) return $this->returnData('notes',$notes);
            else return $this->returnError("user haven't any note");

        }else return $this->returnError('user not found');
    }
}
