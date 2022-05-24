<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\image;
use App\Models\User;
use App\Services\FCMService;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;

class UserController extends Controller
{
    /**
     * @OA\Post(
     * path="/login",
     * summary="login",
     * description="Login by email, password",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     )
     * )
     */
    use GeneralTrait;


    // PROFILE API
    public function profile(): JsonResponse
    {
        return $this->returnData('User Profile information',auth()->user());

    }
    // GET ALL USER
    public function AllUsers(): JsonResponse
    {
        $users = User::paginate(1);
        return $this->returnData("get all users",$users);
    }
    //GET USER BY ID
    public function GetUserById($id): JsonResponse
    {
        $user = User::find($id);
        if( isset($user) )
        {
            return $this->returnData('get user','data',$user);
        }
        else{
            return $this->returnError('user not found');
        }
    }

    ///UpdateProfile
    public function Update(Request $request)
    {
        // validation
        $request->validate([
            "name" => "required",
        ]);


        $user = auth()->user();
        if(isset($user))
        {
                $user->name = $request->name;
                $user->save();
                return $this->returnSuccessMessage('success');
        }else return $this->returnError('something went wrong');

    }
    //Upload image
    public function uploadImage(Request $request)
    {

        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('Images/User'), $imageName);

        $save = new Image;
        $save->user_id = auth()->user()->id;
        $save->name = $imageName;
        $save->save();
        return $this->returnSuccessMessage(200,'Success');
    }
    //get Image
    public function  getImage()
    {
        $image = auth()->user()->image->name;
        $myFile = public_path("Images/User".$image);
        return response()->download($myFile);
    }
    //test
    public function test($test)
    {

        return $this->returnData("Success",$test);
    }
}

