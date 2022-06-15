<?php


namespace App\Traits;


use App\Services\FCMService;
use Illuminate\Http\JsonResponse;

trait GeneralTrait
{
    public function returnError(  $msg = "error" , $errNum = 501  ): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => $errNum,
            'msg' => $msg
        ],$errNum);
    }

    public function returnSuccessMessage(  $msg = "Success" , $errNum = 200 ): JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => $errNum,
            'msg' => $msg
        ],$errNum);
    }

    public function returnData( $msg ,  $value , $key = "Data" , $errNum = 200 , ): JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => $errNum,
            'msg' => $msg,
            $key => $value
        ], $errNum);
    }

    public  function sendnotification($fcm_token,$title,$body,$message = ""): bool
    {
        return FCMService::send(
            $fcm_token,
            [
                'title' => $title,
                'body' => $body,
            ],
            [
                'message' => $message,
            ],
        );
    }

    public function BelongsToCircle( $radius,$c_lat,$c_long,$lat,$long ): bool
    {
        if ( ( ($lat-$c_lat) * ($lat-$c_lat) + ($long - $c_long) * ($long - $c_long) ) <= ($radius * $radius) )return true;
        else return false;
    }


}
//// (x-center_x)^2 + (y - center_y)^2 < radius^2
