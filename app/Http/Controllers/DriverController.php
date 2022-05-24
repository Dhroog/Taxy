<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Driver;
use App\Models\Jobapplication;
use App\Models\Role_User;
use App\Models\Updatedriverinfoapplication;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class DriverController extends Controller
{
    use GeneralTrait;

    public function AllDrivers(): JsonResponse
    {
        $users = User::where('type' ,'=','driver')->with('driver')->paginate(15);
        return $this->returnData("get all driver",$users);
    }

    public function DriverJobApplication(Request $request): JsonResponse
    {
        $request->validate([
            "surname" => "required|regex:/^[a-zA-ZÑñ\s]+$/",
            "age" => "required|int",
            "carmodel" => "string",
            "carcolor" => "string",
            "carnumber" => "string|size:8|unique:jobapplications"
        ]);

        $user = auth()->user();
        if( isset($user) )
        {
            // create data
            $jobApplication = new Jobapplication();
            $jobApplication->user_id = $user->id;
            $jobApplication->surname = $request->surname;
            $jobApplication->age = $request->age;
            $jobApplication->carmodel = $request->carmodel;
            $jobApplication->carcolor = $request->carcolor;
            $jobApplication->carnumber = $request->carnumber;
            $jobApplication->save();

            return $this->returnSuccessMessage();
        }else return $this->returnError();



    }

    public function AllJobApplication(): JsonResponse
    {
        $jobapplication = Jobapplication::paginate(15);
        return $this->returnData("All JobApplication",$jobapplication);
    }

    public function GetStatusDriverJobApplication($id): JsonResponse
    {
        $DriverJobApplication = Jobapplication::find($id);
        if(isset($DriverJobApplication))
        {
            return $this->returnData("Success",$DriverJobApplication->status);
        }else return $this->returnError("JobApplication not found");
    }

    public function AcceptOrRejectDriverJobApplication(Request $request): JsonResponse
    {
        $request->validate([
            'AcceptOrReject'=>'boolean|required',
            'id'=>'int|required',
        ]);

        $DriverJobApplication = Jobapplication::find($request->id);
        if( isset($DriverJobApplication) )
        {
            if( $DriverJobApplication->status == "waiting" )
            {
                $user = User::find( $DriverJobApplication->user_id );
                if( $request->AcceptOrReject )
                {
                    //////////Here Accept Application


                    ////Update user
                    $user->type = "driver";
                    $user->save();


                    ////Create driver
                    $driver = new Driver();
                    $driver->user_id = $user->id;
                    $driver->surname = $DriverJobApplication->surname;
                    $driver->age = $DriverJobApplication->age;
                    $driver->save();

                    ////Create role
                    $role = new Role_User();
                    $role->user_id = $user->id;
                    $role->role_id = 2;
                    $role->save();

                    ////update status DriverJobApplication
                    $DriverJobApplication->status = "accept";
                    $DriverJobApplication->save();

                    $this->sendnotification($user->fcm_token,"Accept your Application","Welcome! we are delighted you've decided to join our company. we are confident that you will bring fresh insights and great work to our team. ");
                    return $this->returnSuccessMessage();


                }else{
                    //////////here reject Application
                    $DriverJobApplication->status = "reject";
                    $DriverJobApplication->save();

                    $this->sendnotification($user->fcm_token,"Reject your Application","you can see reasons rejection in app");
                    return $this->returnSuccessMessage();

                }
            }else return $this->returnError();
        }else return $this->returnError("JobApplication not found");


    }

    public function UpdateDriverInfoApplication(Request $request)
    {
        $request->validate([
            'name'=> "required|regex:/^[a-zA-ZÑñ\s]+$/",
            'surname'=> "required|regex:/^[a-zA-ZÑñ\s]+$/",
            "age" => "required|int",
        ]);

        $driver = auth()->user()->driver;
        if(isset($driver))
        {
            $info = new Updatedriverinfoapplication();
            $info->driver_id = $driver->id;
            $info->name = $request->name;
            $info->surname = $request->surname;
            $info->age = $request->age;
            $info->save();
            return $this->returnSuccessMessage();

        }else return $this->returnError("driver not found");
    }
}
