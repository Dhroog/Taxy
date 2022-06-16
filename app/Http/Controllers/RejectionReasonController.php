<?php

namespace App\Http\Controllers;

use App\Models\Cancellation_reason;
use App\Models\Driver;
use App\Models\Rejection_reason;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RejectionReasonController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
        ]);

        $reason = new Rejection_reason();
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

        $reason = Rejection_reason::find($request->id);
        if( isset($reason) )
        {
            $reason->name = $request->name;
            $reason->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }

    public function delete($id): JsonResponse
    {
        $reason = Rejection_reason::find($id);
        if( isset($reason) )
        {
            $reason->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }

    public function GetAllRejectionReasons(): JsonResponse
    {
        $reason = Rejection_reason::all();
        if( isset($reason) )
        {
            return $this->returnData("get all reasons",$reason);
        }else return $this->returnError("reasons not found");
    }

    public function SendRejectionReason(Request $request): JsonResponse
    {
        $request->validate([
            'trip_id' => 'required|int',
            'driver_id' => 'required|int',
            'rejection_id' => 'required'
        ]);

        $driver = Driver::find($request->driver_id);
        if(isset($driver))
        {
            $driver->rejection()->where('trip_id','=',$request->trip_id)->first()->Rejection_reason()->attach($request->rejection_id);
            return $this->returnSuccessMessage();
        }else return $this->returnError('this driver not found');
    }
}
