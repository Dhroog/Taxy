<?php

namespace App\Http\Controllers;

use App\Models\Reason;
use App\Traits\GeneralTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ReasonController extends Controller
{
    use GeneralTrait;
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
        ]);

        $reason = new Reason();
        $reason->name = $request->name;
        $reason->save();
        return $this->returnSuccessMessage();
    }

    public function edit(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' =>'required|string',
            'id' =>   'required|int'
        ]);

        $reason = Reason::find($request->id);
        if( isset($reason) )
        {
            $reason->name = $request->name;
            $reason->save();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $reason = Reason::find($id);
        if( isset($reason) )
        {
            $reason->delete();
            return $this->returnSuccessMessage();
        }else return $this->returnError("reason not found");

    }

    public function GetAllReasons(): \Illuminate\Http\JsonResponse
    {
        $reason = Reason::all();
        if( isset($reason) )
        {
            return $this->returnData("get all reasons",$reason);
        }else return $this->returnError("reasons not found");
    }
}
