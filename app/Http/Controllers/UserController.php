<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


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
        $users = User::paginate(15);
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
        $user = User::find($id);

        $array = array([
            'message'=>'your trip is accept',
            'trip_id'=>$user->id
        ]);

        $this->sendnotification($user->fcm_token,'test',json_encode($array));
        return $this->returnData('rr',$user);
    }
    public function SendNote(Request $request): JsonResponse
    {
        $request->validate([
            'body' => 'required|string'
        ]);
        $note = new Note();
        $note->user_id = auth()->user()->id;
        $note->title = $request->title;
        $note->body = $request->body;
        $note->save();
        return $this->returnSuccessMessage();
    }
}

