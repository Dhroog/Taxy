<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Driver;
use App\Models\image;
use App\Models\Trip;
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

            return $this->returnData('get user',$user);
        }
        else{
            return $this->returnError('user not found');
        }
    }
    ///UpdateProfile
    public function Update(Request $request): JsonResponse
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
                return $this->returnSuccessMessage();
        }else return $this->returnError('something went wrong');

    }
    //Upload image
    public function UploadImage(Request $request): JsonResponse
    {

        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('Images/User'), $imageName);

        $user = auth()->user();
        $user->image = $imageName;
        $user->save();

        return $this->returnSuccessMessage();
    }
    //get Image
    public function  GetImageById($id): \Symfony\Component\HttpFoundation\BinaryFileResponse|JsonResponse
    {
        $user = User::find($id);
        if( isset($user) )
        {
            if(isset($user->image))
            {

                return $this->returnData('url image',$user->image);
            }else return $this->returnError('image not found');
        }else return  $this->returnError('user not found');

    }
    //test
    public function test($id): JsonResponse
    {
        $trip = Trip::find($id);
        $pos = $trip->position;
        $distance = 0;
        for($i = 0 ; $i <  count($pos)-1 ; $i++ )
        {
            $distance += $this->DistanceBetweenTowPoint($pos[$i]->lat,$pos[$i]->long,$pos[$i+1]->lat,$pos[$i+1]->lat,'k');
        }
        //$distance = $this->DistanceBetweenTowPoint(0,0,50,50,'m');
        return $this->returnData('what',$distance);
    }
}

