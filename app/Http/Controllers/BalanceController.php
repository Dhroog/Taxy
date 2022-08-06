<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Reward;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    use GeneralTrait;
    public function ChargeDriverBalance(Request $request): JsonResponse
    {
        $request->validate([
           'driver_id' => 'required|int',
           'amount' => 'required'
        ]);

        $driver = Driver::find($request->driver_id);
        if(isset($driver))
        {
            $balance = $driver->balance;
            $balance->amount += $request->amount;
            $balance->save();
            $notification = 'your account is charged by : '.$request->amount.'your total balance now is : '.$balance->amount;
            $this->sendnotification($driver->user->fcm_token,'Charge balance',$notification);
            return $this->returnSuccessMessage();
        }else return $this->returnError('driver not found');
    }

    public function GetDriverBalance($id): JsonResponse
    {
        $user = User::find($id);
        if( isset($user))
        {
            $driver = $user->driver;
            if(isset($driver))
            {
                $array = array([
                    'balance' => $driver->balance->amount,
                    'lastPay' => 10,
                    'tripsCount' => 15
                ]);
                return $this->returnData('your Balance ',$array);
            }else return $this->returnError('driver not found');
        }else return $this->returnError('driver not found');

    }

    public function DiscountDriverBalance(Request $request): JsonResponse
    {

        $request->validate([
            'driver_id' => 'required|int',
            'amount' => 'required'
        ]);

        $driver = Driver::find($request->driver_id);
        if(isset($driver))
        {
            $balance = $driver->balance;
            $balance->amount -= $request->amount;
            $balance->save();
            $notification = 'your account is Discount by : '.$request->amount.'your total balance now is : '.$balance->amount;
            $this->sendnotification($driver->user->fcm_token,'Charge balance',$notification);
            return $this->returnSuccessMessage();
        }else return $this->returnError('driver not found');
    }

    public function RewardDriverBalance(Request $request)
    {
        $request->validate([
            'driver_id' => 'required|int',
            'description' => 'required|string',
            'amount' => 'required'
        ]);

        $driver = Driver::find($request->driver_id);
        if(isset($driver))
        {
            $balance = $driver->balance;
            $balance->amount += $request->amount;
            $balance->save();
            $reward = new Reward();
            $reward->balance_id = $balance->id;
            $reward->description = $request->description;
            $reward->amount = $request->amount;
            $reward->save();
            $notification = 'your account is charged by : '.$request->amount.'your total balance now is : '.$balance->amount;
            $this->sendnotification($driver->user->fcm_token,'Charge balance',$notification);
            return $this->returnSuccessMessage();
        }else return $this->returnError('driver not found');
    }
}
