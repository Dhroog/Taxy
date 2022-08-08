<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use GeneralTrait;
    public function SendNotificationAll(Request $request){
        $request->validate([
            'to' => 'required|string|in:driver,customer,admin,user',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if($request->to == 'admin'){
            ////admin
            $admins = User::select('fcm_token')->where('type','=','admin')->get();
            foreach ($admins as $admin)
                $this->sendnotification($admin->fcm_token,$request->title,$request->body);
            return $this->returnSuccessMessage();

        }else if($request->to == 'driver'){
            ///driver
            $drivers = User::select('fcm_token')->where('type','=','driver')->get();
            foreach ($drivers as $driver)
                $this->sendnotification($driver->fcm_token,$request->title,$request->body);
            return $this->returnSuccessMessage();
        }else if($request->to == 'customer'){
            //customer
            $customers = User::select('fcm_token')->where('type','=','customer')->get();
            foreach ($customers as $customer)
                $this->sendnotification($customer->fcm_token,$request->title,$request->body);
            return $this->returnSuccessMessage();
        }else {
            ///user
            $users = User::select('fcm_token')->get();
            foreach ($users as $user)
                $this->sendnotification($user->fcm_token,$request->title,$request->body);
            return $this->returnSuccessMessage();
        }
    }
    public function SendNotificationByID(Request $request)
    {
        $request->validate([
            'id' => 'required|int',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);
        $user = User::find($request->id);
        if(isset($user)){
            if(!$this->sendnotification($user->fcm_token,$request->title,$request->body))return $this->returnError();
            return $this->returnSuccessMessage();
        }else return $this->returnError('user not found');
    }
}
