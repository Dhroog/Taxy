<?php

namespace App\Http\Controllers;

use App\Models\Cancellation_reason;
use App\Models\Reason;
use App\Models\Trip;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CancellationReasonController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
        ]);

        $reason = new Cancellation_reason();
        $reason->name = $request->name;
        $reason->save();
        return $this->returnSuccessMessage();
    }

    public function edit(Request $request): JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
            'id' =>   'required|int'
        ]);

        $reason = Cancellation_reason::find($request->id);
        if( isset($reason) )
        {
            $reason->name = $request->name;
            $reason->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }

    public function delete($id): JsonResponse
    {
        $reason = Cancellation_reason::find($id);
        if( isset($reason) )
        {
            $reason->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }

    public function GetAllCancellationReasons(): JsonResponse
    {
        $reason = Cancellation_reason::all();
        if( isset($reason) )
        {
            return $this->returnData("get all reasons",$reason);
        }else return $this->returnError("reasons not found");
    }

    public function SendCancellationReason(Request $request): JsonResponse
    {
        $request->validate([
           'trip_id' => 'required|int',
           'cancellation_id'=>'required'
        ]);

        $trip = Trip::find($request->trip_id);
        if(isset($trip))
        {
            if(!$trip->sarted)
            {
                if(!$trip->canceled)
                {

                    //update trip
                    $trip->canceled = true;
                    $trip->save();
                    //send notification to driver
                    if($trip->accepted)
                    {
                        $driver = $trip->driver;
                        $driver->available = true;
                        $driver->save();
                        $this->sendnotification($driver->user->fcm_token,'Cancellation Trip','Client Cancel your Trip');
                    }
                    $trip->Cancellation_reason()->attach($request->cancellation_id);
                    return  $this->returnSuccessMessage();
                }else return $this->returnError('this trip already canceled');
            }else return $this->returnError('this trip is started ');

        }else return $this->returnError('Trip not found');
    }

    public function StartTrip($trip_id)
    {
        $driver = auth()->user()->driver;
        if(isset($driver))
        {
            $trip = Trip::find($trip_id);
            if(isset($trip))
            {
                if(!$trip->started)
                {
                    if(!$trip->canceled)
                    {
                        $trip->started = true;
                        $trip->save();
                        $this->sendnotification($trip->user->fcm_token,'Trip Started','Your Trip is started now ');
                        return  $this->returnSuccessMessage('trip is started now');
                    }else return $this->returnError('this trip is cancel');
                }else return $this->returnError('this trip already started');

            }else return $this->returnError('trip not found');

        }$this->returnError('you are not driver');
    }
}
