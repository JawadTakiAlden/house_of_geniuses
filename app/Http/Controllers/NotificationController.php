<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendNotificationRequest;
use App\HttpResponse\HTTPResponse;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{


    use HTTPResponse;
    public function BasicSendNotification($title , $body , $FcmToken){


//        apiKey: "AIzaSyCquL3Sf2JDpqYPyawJ4s9Kl5g_czjZ3gw",
//  authDomain: "houseofgeniuses-7bb6b.firebaseapp.com",
//  projectId: "houseofgeniuses-7bb6b",
//  storageBucket: "houseofgeniuses-7bb6b.appspot.com",
//  messagingSenderId: "895515853250",
//  appId: "1:895515853250:web:12e265a89b3c490ceb965b",
//  measurementId: "G-8XC6K5DPR2"
//        key : BHXu4tMNISTlQGe_QsfvbrNN2GpAbF0U2tHgH9kN-vkhEn0rtL9u3_6C_NXuYHm519cHAP3BX_Dx4yT2ZWqedYw

        $url = 'https://fcm.googleapis.com/fcm/send';

        $server_key = "AAAAhggAXOM:APA91bFlPweIZXPYUlmObCTFaQlvzm3Op6G_fyq9RJcpN81kmekCaEz00vbhnW3dQLZredAQYCD1qg6kswC5H0ZAuNrucMnEqNVGfnyGp5woSd7_WENU7zNWOHIE-CiUBmXalBr_btCC";

        $date = [
            'registration_ids' => $FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body
            ]
        ];

        $encodedData = json_encode($date);

        $headers = [
            'Authorization: key='.$server_key,
            'Content-Type: application/json'
        ];

        $ch = curl_init();

        curl_setopt($ch , CURLOPT_URL , $url);
        curl_setopt($ch , CURLOPT_POST , true);
        curl_setopt($ch , CURLOPT_HTTPHEADER , $headers);
        curl_setopt($ch , CURLOPT_RETURNTRANSFER , true);
        curl_setopt($ch , CURLOPT_SSL_VERIFYHOST , 0);
        curl_setopt($ch , CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
        curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , false);
        curl_setopt($ch , CURLOPT_POSTFIELDS , $encodedData);

        $result = curl_exec($ch);

        if ($result === FALSE){
            return $this->error(curl_error($ch) , 500);
        }

        curl_close($ch);

        return $this->success($result , 'notification send successfully');
    }

    public function sendNotificationForAllUser(SendNotificationRequest $request){
        try {
            $tokens = User::whereNotNull('device_notification_id')->pluck('device_notification_id')->all();
            $result = $this->BasicSendNotification($request->title , $request->body , $tokens);
            return $result;
        }catch (\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function addNewCourseNotification($course){
        try {
            $title = 'اضافة دورة تدريبة جديدة جديد';
            $body =  'تمت اضافة دورة تدريبة جديدة تحت عنوان ' . $course->name;
            $tokens = User::whereNotNull('device_notification_id')->pluck('device_notification_id')->all();
            $result = $this->BasicSendNotification($title, $body , $tokens);
            return $result;
        }catch (\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
}
