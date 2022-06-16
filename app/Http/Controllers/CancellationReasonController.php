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
            $trip->Cancellation_reason()->attach($request->cancellation_id);
            return  $this->returnSuccessMessage();
        }else return $this->returnError('Trip not found');
    }
}
