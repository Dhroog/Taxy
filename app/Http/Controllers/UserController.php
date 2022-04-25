<?php

namespace App\Http\Controllers;

use App\Mail\VerfyEmail;
use App\Models\Code;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Auth\Events\Registered;
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

    public function role(): JsonResponse
    {

        $user = User::find(1);
        $a = array();
        $abilitys = $user->role;
        foreach ($abilitys as $ability)
        {
            array_push($a,$ability->name);
        }

        return response()->json($a);

    }

    // LOGIN API
    public function login(Request $request): JsonResponse
    {

        // validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // check user
        $user = User::where("email", "=", $request->email)->first();
        if(isset($user->id)){

            if(Hash::check($request->password, $user->password)){

                //store abilities this user in array
                $abilitys = $user->role;
                $arry = array();
                foreach ($abilitys as $ability)
                {
                    array_push($arry,$ability->name);
                }
                // create a token
                $token = $user->createToken("auth_token",$arry)->plainTextToken;

                /// send a response
                return $this->returnData(200,'logged in successfully','access_token',$token);

            }else{

                return $this->returnError(500,"Password didn't match");
            }
        }else{

            return $this->returnError(500,"User not found");

        }
    }
    // REGISTER API
    public function register(Request $request): JsonResponse
    {

        // validation
        $request->validate([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        // create data
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = false;
        $user->password = Hash::make($request->password);

        $user->save();

        //$user->code->create(['code'=> uniqid(),'user_id'=>$user->id]);
        $code = new Code();
        $code->code = uniqid();
        $code->user_id = $user->id;
        $code->save();

        // send response
        return $this->returnSuccessMessage(200,'Success register');
    }
    // LOGOUT API
    public function logout(): JsonResponse
    {

        auth()->user()->tokens()->delete();

        return $this->returnSuccessMessage(200,'logged out successfully');
    }

    // PROFILE API
    public function profile()
    {
        return $this->returnData(200,'User Profile information','data',auth()->user());

    }


}
