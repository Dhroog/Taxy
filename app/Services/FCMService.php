<?php
namespace App\Services;

use phpDocumentor\Reflection\Types\Boolean;

class FCMService
{
    public static  function  send( $token,$notification,$data )
    {
        $fields = [
            'to' => $token,
            'priority'=> 10,
            'notification' => $notification,
            'data' => $data,
            'vibrate' => 1,
            'sound' => 1
        ];
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: key='. config('fcm.fcm_token')
        ];

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);//echo $result;
        if($err)return false;else return true;


    }
}
