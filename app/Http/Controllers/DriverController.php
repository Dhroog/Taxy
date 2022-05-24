<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Driver;
use App\Models\Jobapplication;
use App\Models\Role_User;
use App\Models\User;
use App\Traits\GeneralTrait;
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

    public function DriverJobApplication(Request $request)
    {
        $request->validate([
            "name" => "required|regex:/^[a-zA-ZÑñ\s]+$/",
            "surname" => "required|regex:/^[a-zA-ZÑñ\s]+$/",
            "age" => "required",
            "phone" => "required|size:10|unique:users|unique:jobapplications",
            "carmodel" => "string",
            "carcolor" => "string",
            "carnumber" => "string|size:8|unique:jobapplications"
        ]);

        // create data
        $jobApplication = new Jobapplication();
        $jobApplication->name = $request->name;
        $jobApplication->surname = $request->surname;
        $jobApplication->age = $request->age;
        $jobApplication->phone = $request->phone;
        $jobApplication->carmodel = $request->carmodel;
        $jobApplication->carcolor = $request->carcolor;
        $jobApplication->carnumber = $request->carnumber;
        $jobApplication->save();

        return $this->returnSuccessMessage('Success');

    }

    public function AllJobApplication()
    {
        $jobapplication = Jobapplication::paginate(15);
        return $this->returnData(200,'Success','data',$jobapplication);
    }

    public function GetStatusDriverJobApplication($id)
    {
        $DriverJobApplication = Jobapplication::find($id);
        if(isset($DriverJobApplication))
        {
            return $this->returnData("Success",$DriverJobApplication->status);
        }else return $this->returnError("JobApplication not found");
    }

    public function AcceptOrRejectDriverJobApplication(Request $request)
    {
        $request->validate([
            'AcceptOrReject'=>'boolean|required',
            'id'=>'int|required',
        ]);

        $DriverJobApplication = Jobapplication::find($request->id);
        if( $DriverJobApplication->status == "waiting" )
        {
            if(isset($DriverJobApplication))
            {
                if( $request->AcceptOrReject )
                {
                    //////////Here Accept Aapplaction
                    $password = Str::random(8);

                    ////Create user
                    $user = new User();
                    $user->name = $DriverJobApplication->name;
                    $user->phone = $DriverJobApplication->phone;
                    $user->password = Hash::make($password);
                    $user->status = true;
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

                    ////Create code
                    $code = new Code();
                    $code->user_id = $user->id;
                    $code->code = random_int(100000,999999);
                    $code->save();

                    ////update status DriverJobApplication
                    $DriverJobApplication->status = "accept";
                    $DriverJobApplication->save();

                    $this->sendnotification("cw7BskxnSZKu1UAQd6hIIh:APA91bE74j1vRVX5uuRDBoeRzFhFqWB5Ep8WH_8ZzcDYSPfnNQ5wYyGaiTm8k9cKbWm5gcLcOfV7ruyun02EWcpvxaDgW0ci0iC1AXRHfcLrN7CrWyE3muGj4Pv5XkE9P7Vh_l-5DXQB","Accept your Application","your password is : ".$password);
                    return $this->returnSuccessMessage();


                }else{
                    //////////here reject Application
                    $DriverJobApplication->status = "reject";
                    $DriverJobApplication->save();

                    $this->sendnotification("cw7BskxnSZKu1UAQd6hIIh:APA91bE74j1vRVX5uuRDBoeRzFhFqWB5Ep8WH_8ZzcDYSPfnNQ5wYyGaiTm8k9cKbWm5gcLcOfV7ruyun02EWcpvxaDgW0ci0iC1AXRHfcLrN7CrWyE3muGj4Pv5XkE9P7Vh_l-5DXQB","Reject your Application","you can see reasons rejection in app");
                    return $this->returnSuccessMessage();

                }
            }else return $this->returnError("JobApplication not found");
        }else return $this->returnError();


    }
}
