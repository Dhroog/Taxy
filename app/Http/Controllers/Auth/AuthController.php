<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Code;
use App\Models\User;
use App\Traits\GeneralTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    use GeneralTrait;


    /**
     * register a new customer.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * login user and store his fcm_token in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // validation
        $request->validate([

            "phone" => "required|size:10",
            "password" => "required",
            "fcm_token" => "required|string",
        ]);

        // check user
        $user = User::where("phone", "=", $request->phone)->first();
        if (isset($user->id)) {

            if (Hash::check($request->password, $user->password)) {
                $user->fcm_token = $request->fcm_token;
                $user->save();


                // create a token
                $token = $user->createToken("auth_token")->plainTextToken;
                $job = $user->jobapplication;
                if( $user->type == 'customer' && isset($job)) {
                    $arry = array(
                            'access_token' => $token,
                            'user_id' => $user->id,
                            'type' => $user->type,
                            'jobapplication_id' => $job->id,
                            'active' => $user->status,
                            'banned' => $user->banned
                        );
                        /// send a response
                        return $this->returnData('logged in successfully', $arry);
                }else {
                    $arry = array(
                        'access_token' => $token,
                        'user_id' => $user->id,
                        'type' => $user->type,
                        'active' => $user->status,
                        'banned' => $user->banned
                    );
                    /// send a response
                    return $this->returnData('logged in successfully', $arry  );
                }


            } else {

                return $this->returnError( "Password incorrect");
            }
        } else {

            return $this->returnError( "phone incorrect");

        }
    }
    /**
     * verify Account user and make his account active .
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            $job = $user->jobapplication;
            if( $user->type == 'customer' && isset($job)) {
                $arry = array(
                    'type' => $user->type,
                    'jobapplication_id' => $job->id,
                );
                /// send a response
                return $this->returnData('Success verifyPhone', $arry);
            }else {
                $arry = array(
                    'type' => $user->type,
                );
                /// send a response
                return $this->returnData('Success verifyPhone', $arry  );
            }
        } else return $this->returnError( 'code not correct');
    }
    /**
     * logout user and delete his token .
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {

        //auth()->user()->tokens()->delete();
        $user = auth()->user();
        $user->tokens()->delete();
        $user->fcm_token = null;
        $user->save();

        return $this->returnSuccessMessage( 'logged out successfully');
    }

    /**
     * send notification to user have code for used for
     * active  his account or change password.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
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
        }else {
            $e = 120-$time_updated->diffInSeconds($time_now);
            return $this->returnData("can't send code now wait ".$e." sec",120-$time_updated->diffInSeconds($time_now),'data',501);
        }


    }
    /**
     *  send notification to user have code for used for
     * active  his account or change password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
            if(isset($request->fcm_token))
            {
                $user->fcm_token = $request->fcm_token;
                $user->save();
            }


            if( $time_created->diffInMinutes($time_updated) == 0 || $time_now->diffInMinutes($time_updated) >= 2 )
            {
                $code->code = random_int(100000,999999);
                $code->save();
                if( $this->sendnotification($request->fcm_token,'Code Verification',$code->code,'') )
                return $this->returnSuccessMessage("we send code to your phone");
                else return $this->returnError("fail send notification");
            }else {
                $e = 120-$time_updated->diffInSeconds($time_now);
                return $this->returnData("can't send code now wait ".$e." sec",120-$time_updated->diffInSeconds($time_now),'data',501);
            }

        }else return $this->returnError('something went wrong with phone ');
    }
    /**
     * change password for user with code
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ResetPassword(Request $request): JsonResponse
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
    /**
     * change password for user with old password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * insert FCM_TOKEN user in database
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function InsertTokenFireBase(Request $request): JsonResponse
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
