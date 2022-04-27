<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\VerfyEmail;
use App\Models\Code;
use App\Models\User;
use App\Traits\GeneralTrait;
use http\Env\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mail;


class AuthController extends Controller
{
    use GeneralTrait;


    // REGISTER API
    public function register(Request $request): JsonResponse
    {
        /**
         * @OA\Post  (
         * path="/register",
         * summary="signup",
         * description="signup by email, password",
         * operationId="authRegister",
         * tags={"auth"},
         * @OA\RequestBody(
         *    required=true,
         *    description="Pass user credentials",
         *    @OA\JsonContent(
         *       required={ "name","email","password"},
         *       @OA\Property(property="name", type="string", format="name", example="user1"),
         *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
         *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
         *       @OA\Property(property="password_confirmation", type="string", format="password_confirmation", example="PassWord12345"),
         *
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

        $code = new Code();
        $code->code = random_int(100000,999999);
        $code->user_id = $user->id;
        $code->save();

        mail::to($user)->send(new VerfyEmail($code->code));

        // send response
        return $this->returnSuccessMessage(200, 'Success register we send a code to your email ');
    }


    /**
     * @param Request $request jhdsaj
     * @return JsonResponse saed
     */
    public function login(Request $request): JsonResponse
    {
        // validation
        $request->validate([

            "email" => "required|email",
            "password" => "required"
        ]);

        // check user
        $user = User::where("email", "=", $request->email)->first();
        if (isset($user->id)) {

            if (Hash::check($request->password, $user->password)) {

                //store abilities this user in array
                $abilitys = $user->role;
                $arry = array();
                foreach ($abilitys as $ability) {
                    array_push($arry, $ability->name);
                }
                // create a token
                $token = $user->createToken("auth_token", $arry)->plainTextToken;

                /// send a response
                return $this->returnData(200, 'logged in successfully', 'access_token', $token);

            } else {

                return $this->returnError(400, "Password didn't match");
            }
        } else {

            return $this->returnError(400, "User not found");

        }
    }

    //verifyemail
    public function VerifyEmail(Request $request)
    {
        // validation
        $request->validate([
            "code" => "required|int",
        ]);

        if ( auth()->user()->code->code == $request->code ) {
            $user = auth()->user();
            $user->status = true;
            $user->save();
            return $this->returnSuccessMessage(200, 'Success verifyemail ');
        } else return $this->returnError(501, 'code not correct');


    }

    // LOGOUT API
    public function logout(): JsonResponse
    {

        auth()->user()->tokens()->delete();

        return $this->returnSuccessMessage(200, 'logged out successfully');
    }

    //resend code
    public function ReSendCode()
    {
        $code = auth()->user()->code;
        $code->code = random_int(100000,999999);
        $code->save();

        $this->returnData(200,"sucssec",'data',$code->code);

    }

}
