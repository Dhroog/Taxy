<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\rr;
use App\Mail\VerfyEmail;
use App\Models\Code;
use App\Models\Role_User;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
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

        // validation
        $request->validate([
            "name" => "required",
            "phone" => "required|size:10|unique:users",
            "password" => "required|confirmed"
        ]);

        // create data
        $user = new User();

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->status = false;
        $user->password = Hash::make($request->password);
        $user->save();

        $code = new Code();
        $code->code = random_int(100000,999999);
        $code->user_id = $user->id;
        $code->save();

        $role = new Role_User();
        $role->user_id = $user->id;
        $role->role_id = 3;
        $role->save();



        // send response
        return $this->returnSuccessMessage('Success');
    }
   // LOGIN
    public function login(Request $request): JsonResponse
    {
        // validation
        $request->validate([

            "phone" => "required|size:10",
            "password" => "required"
        ]);

        // check user
        $user = User::where("phone", "=", $request->phone)->first();
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
                return $this->returnData('logged in successfully', $token , 'access_token' );

            } else {

                return $this->returnError( "Something went wrong");
            }
        } else {

            return $this->returnError( "Something went wrong");

        }
    }
    //verified
    public function VerifyPhone(Request $request)
    {
        // validation
        $request->validate([
            "code" => "required|int",
        ]);

        if ( auth()->user()->code->code == $request->code ) {
            $user = auth()->user();
            $user->status = true;
            $user->save();
            return $this->returnSuccessMessage( 'Success verifyPhone ');
        } else return $this->returnError( 'code not correct');


    }
    // LOGOUT API
    public function logout(): JsonResponse
    {

        //auth()->user()->tokens()->delete();
        $user = auth()->user();
        $user->tokens()->delete();
        $user->fcm_token = null;
        $user->save();

        return $this->returnSuccessMessage( 'logged out successfully');
    }
    //resend code
    public function ReSendCode()
    {
        $code = auth()->user()->code;

        $time_now = Carbon::instance(Carbon::now());
        $time_updated = Carbon::instance($code->updated_at);
        $time_created = Carbon::instance($code->created_at);

        if( $time_now->diffInMinutes($time_updated) >= 0 || $time_now->diffInMinutes($time_created) <= 2 )
        {
            $code->code = random_int(100000,999999);
            $code->save();
            if($this->sendnotification(auth()->user()->fcm_token,'Code Verification',$code->code,''))
            return $this->returnSuccessMessage("Success");
            else return $this->returnError("fail send notification");
        }else return $this->returnData("can't send code now",2-$time_updated->diffInMinutes($time_now), 501);


    }
    ///ForgetPassword
    public function ForgetPassword(Request $request)
    {
        // validation
        $request->validate([
            "phone" => "required|size:10",
        ]);

        $user = User::where( "phone", "=", $request->phone )->first();
        if( isset($user->id) )
        {
            $code = $user->code;
            $time_now = Carbon::instance(Carbon::now());
            $time_updated = Carbon::instance($code->updated_at);

            if($time_now->diffInMinutes($time_updated) >= 2)
            {
                $code->code = random_int(100000,999999);
                $code->save();
                if( $this->sendnotification($user->fcm_token,'Code Verification',$code->code,'') )
                return $this->returnSuccessMessage("we send code to your phone");
                else return $this->returnError("fail send notification");
            }else return $this->returnData("can't send code now",2-$time_updated->diffInMinutes($time_now),501);

        }else return $this->returnError('something went wrong with phone ');
    }
    ///ResetPassword
    public function ResetPassword(Request $request)
    {
        // validation
        $request->validate([
            "phone" => "required|size:10",
            "code" => "required|int",
            "password" => "required|confirmed"
        ]);

         $user = User::where( "phone", "=", $request->phone )->first();
        if (isset($user->id))
        {
            if( $user->code->code == $request->code )
            {
                $user->password = Hash::make($request->password);
                $user->save();
                return $this->returnSuccessMessage('success change password');
            } else return $this->returnError(' wrong code ');
        }else return $this->returnError('wrong phone');
    }

    //InsertTokenFireBase
    public function InsertTokenFireBase(Request $request)
    {
        ///validation
        $request->validate([
            "fcm_token" => "required|string",
        ]);

        $user = auth()->user();
        if( isset($user) )
        {
            $user->fcm_token = $request->fcm_token;
            $user->save();
            return $this->returnSuccessMessage('Success');
        }else return $this->returnError('something went wrong');
    }


}
