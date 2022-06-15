<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


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

        $user->assignRole('Customer');



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
                /*
                    $abilitys = $user->role;
                    $arry = array();
                    foreach ($abilitys as $ability) {
                        array_push($arry, $ability->name);
                    }
                */
                // create a token
                $token = $user->createToken("auth_token")->plainTextToken;
                $arry = array(
                    'access_token' => $token,
                    'user_id' => $user->id
                );

                /// send a response
                return $this->returnData('logged in successfully', $arry  );

            } else {

                return $this->returnError( "Something went wrong");
            }
        } else {

            return $this->returnError( "Something went wrong");

        }
    }
    //verified
    public function VerifyPhone(Request $request): JsonResponse
    {
        // validation
        $request->validate([
            "code" => "required|size:6",
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
    public function ReSendCode(): JsonResponse
    {
        $code = auth()->user()->code;

        $time_now = Carbon::instance(Carbon::now());
        $time_updated = Carbon::instance($code->updated_at);
        $time_created = Carbon::instance($code->created_at);

        if( $time_created->diffInMinutes($time_updated) == 0 || $time_now->diffInMinutes($time_updated) >= 2 )
        {
            $code->code = random_int(100000,999999);
            $code->save();
            if($this->sendnotification(auth()->user()->fcm_token,'Code Verification',$code->code))
            return $this->returnSuccessMessage();
            else return $this->returnError("fail send notification");
        }else return $this->returnData("can't send code now",120-$time_updated->diffInSeconds($time_now), 501);


    }
    ///ForgetPassword
    public function ForgetPassword(Request $request): JsonResponse
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
            $time_created = Carbon::instance($code->created_at);
            ///insert new fcm token
            $user->fcm_token = $request->fcm_token;
            $user->save();

            if( $time_created->diffInMinutes($time_updated) == 0 || $time_now->diffInMinutes($time_updated) >= 2 )
            {
                $code->code = random_int(100000,999999);
                $code->save();
                if( $this->sendnotification($user->fcm_token,'Code Verification',$code->code,'') )
                return $this->returnSuccessMessage("we send code to your phone");
                else return $this->returnError("fail send notification");
            }else return $this->returnData("can't send code now",120-$time_updated->diffInSeconds($time_now));

        }else return $this->returnError('something went wrong with phone ');
    }
    ///ResetPassword
    public function ResetPassword(Request $request)
    {
        // validation
        $request->validate([
            "phone" => "required|size:10",
            "code" => "required|size:6",
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
    ///ChangePassword
    public function ChangePassword(Request $request): JsonResponse
    {
        $request->validate([
            "phone" => "required|size:10",
            "old_password" => "required",
            "new_password" => "required|confirmed"
        ]);

        $user = User::where( "phone", "=", $request->phone )->first();
        if (isset($user->id))
        {
            if( Hash::check($request->old_password,$user->password)  )
            {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return $this->returnSuccessMessage('success change password');
            } else return $this->returnError(' wrong password ');
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
