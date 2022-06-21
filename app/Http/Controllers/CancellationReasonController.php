<?php

namespace App\Http\Controllers;

use App\Models\Cancellation_reason;
use App\Models\Trip;
use App\Traits\GeneralTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CancellationReasonController extends Controller
{
    use GeneralTrait;
    /**
     * Store a newly created cancellation reason  in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Update the specified cancellation reason in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
    /**
     * Remove the specified cancellation reason from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $reason = Cancellation_reason::find($id);
        if( isset($reason) )
        {
            $reason->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }
    /**
     * Get All cancellation reasons from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetAllCancellationReasons(): JsonResponse
    {
        $reason = Cancellation_reason::paginate();
        if( isset($reason) )
        {
            return $this->returnData("get all reasons",$reason);
        }else return $this->returnError("reasons not found");
    }
    /**
     * Cancel the specified Trip and update it information in Storage
     * and Store cancellation reasons for this Trip in Storage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

}
