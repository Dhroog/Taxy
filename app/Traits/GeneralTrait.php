<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;

trait GeneralTrait
{
    public function returnError( $errNum = 401 , $msg ): JsonResponse
    {
        return response()->json([
            'status' => false,
            'code' => $errNum,
            'msg' => $msg
        ],$errNum);
    }

    public function returnSuccessMessage( $errNum = 200 , $msg ): JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => $errNum,
            'msg' => $msg
        ],$errNum);
    }

    public function returnData( $errNum = 200 , $msg , $key , $value ): JsonResponse
    {
        return response()->json([
            'status' => true,
            'code' => $errNum,
            'msg' => $msg,
            $key => $value
        ], $errNum);
    }
}
