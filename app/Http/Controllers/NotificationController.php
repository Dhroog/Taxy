<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\user_have_notification;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use GeneralTrait;
    public function SendNotificationAll(Request $request): JsonResponse
    {
        $request->validate([
            'to' => 'required|string|in:driver,customer,admin,user',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $notifiaction = new Notification();
        $notifiaction->title = $request->title;
        $notifiaction->body = $request->body;
        $notifiaction->save();

        if($request->to == 'admin'){
            ////admin
            $admins = User::select('fcm_token','id')->where('type','=','admin')->get();
            foreach ($admins as $admin) {
                $this->sendnotification($admin->fcm_token, $request->title, $request->body);
                $user_have_notification = new user_have_notification();
                $user_have_notification->user_id = $admin->id;
                $user_have_notification->notification_id = $notifiaction->id;
                $user_have_notification->save();
            }
            return $this->returnSuccessMessage();

        }else if($request->to == 'driver'){
            ///driver
            $drivers = User::select('fcm_token','id')->where('type','=','driver')->get();
            foreach ($drivers as $driver) {
                $this->sendnotification($driver->fcm_token, $request->title, $request->body);
                $user_have_notification = new user_have_notification();
                $user_have_notification->user_id = $driver->id;
                $user_have_notification->notification_id = $notifiaction->id;
                $user_have_notification->save();
            }
            return $this->returnSuccessMessage();
        }else if($request->to == 'customer'){
            //customer
            $customers = User::select('fcm_token','id')->where('type','=','customer')->get();
            foreach ($customers as $customer) {
                $this->sendnotification($customer->fcm_token, $request->title, $request->body);
                $user_have_notification = new user_have_notification();
                $user_have_notification->user_id = $customer->id;
                $user_have_notification->notification_id = $notifiaction->id;
                $user_have_notification->save();
            }
            return $this->returnSuccessMessage();
        }else {
            ///user
            $users = User::select('fcm_token','id')->get();
            foreach ($users as $user) {
                $this->sendnotification($user->fcm_token, $request->title, $request->body);
                $user_have_notification = new user_have_notification();
                $user_have_notification->user_id = $user->id;
                $user_have_notification->notification_id = $notifiaction->id;
                $user_have_notification->save();
            }
            return $this->returnSuccessMessage();
        }
    }
    public function SendNotificationByID(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|int',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);
        $user = User::find($request->id);
        if(isset($user)){
            if(!$this->sendnotification($user->fcm_token,$request->title,$request->body))return $this->returnError();
            else{
                $notifiaction = new Notification();
                $notifiaction->title = $request->title;
                $notifiaction->body = $request->body;
                $notifiaction->save();
                $user_have_notification = new user_have_notification();
                $user_have_notification->user_id = $user->id;
                $user_have_notification->notification_id = $notifiaction->id;
                $user_have_notification->save();
                return $this->returnSuccessMessage();
            }

        }else return $this->returnError('user not found');
    }
    public function GetAllNotificationsForUser($id): JsonResponse
    {
        $user = User::find($id);
        if(isset($user)){
            $notifications = $user->notifications;
            if(isset($notifications))return $this->returnData('get all user notifications',$notifications);
            else return $this->returnError();
        }else return $this->returnError('user not found');
    }

    public function GetAllUsersForNotification($id): JsonResponse
    {
        $notifications = Notification::find($id);
        if(isset($notifications)){
            $user  = $notifications->users;
            if(isset($user))return $this->returnData('get all user notifications',$user);
            else return $this->returnError();
        }else return $this->returnError('user not found');
    }
}
