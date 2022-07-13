<?php


namespace App\Traits;


use App\Services\FCMService;
use Illuminate\Http\JsonResponse;
use JetBrains\PhpStorm\Pure;

trait GeneralTrait
{
    public function returnError(  $msg = "Error" , $errNum = 501  ): JsonResponse
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
/*
    public function BelongsToCircle( $radius,$c_lat,$c_long,$lat,$long ): bool
    {
        if ( ( ($lat-$c_lat) * ($lat-$c_lat) + ($long - $c_long) * ($long - $c_long) ) <= ($radius * $radius) )return true;
        else return false;
    }
*/
    public function BelongsToCircle($radius, $c_lat, $c_long, $lat, $long ): bool
    {
        if( $this->DistanceBetweenTowPoint($c_lat,$c_long,$lat,$long,'m') <= $radius )return true;
        else return false;

    }

    public function DistanceBetweenTowPoint($lat1,$lon1,$lat2,$lon2,$unit): float
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }
        else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else if($unit == "M"){
                return $miles * 1609.34;
            }else{
                return $miles;
            }
        }

    }


}
//// (x-center_x)^2 + (y - center_y)^2 < radius^2
////1 miles have 1609.34 meters
