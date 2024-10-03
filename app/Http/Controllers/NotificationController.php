<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\SendNotificationRequest;
use App\HttpResponse\HTTPResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{


    use HTTPResponse;
    public function BasicSendNotification($title , $body , $FcmToken){

        $firebase = (new Factory())
            ->withServiceAccount(config_path('firebase_config.json'));

        $this->messaging = $firebase->createMessaging();

        $messageData = [
            'notification' => [
                'title' => $title,
                'body' => $body
            ],
        ];

        $message = CloudMessage::fromArray($messageData);
        $tokenChunks = array_chunk($FcmToken, 500);

        return $this->success($FcmToken);

        try {
            foreach ($tokenChunks as $tokens) {
                $this->messaging->sendMulticast($message, $tokens);
            }
            return $this->success(null, __('messages.notification_controller.send_successfully'));
        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
            return $this->error($e->getMessage() , 500);
        } catch (FirebaseException $e) {
            return $this->error($e->getMessage() , 500);
        }

//        $url = 'https://fcm.googleapis.com/fcm/send';
//        $server_key = config('app.firebase_server_key');
//        $date = [
//            'registration_ids' => $FcmToken,
//            'notification' => [
//                'title' => $title,
//                'body' => $body
//            ]
//        ];
//
//        $encodedData = json_encode($date);
//
//        $headers = [
//            'Authorization: key='.$server_key,
//            'Content-Type: application/json'
//        ];
//
//        $ch = curl_init();
//
//        curl_setopt($ch , CURLOPT_URL , $url);
//        curl_setopt($ch , CURLOPT_POST , true);
//        curl_setopt($ch , CURLOPT_HTTPHEADER , $headers);
//        curl_setopt($ch , CURLOPT_RETURNTRANSFER , true);
//        curl_setopt($ch , CURLOPT_SSL_VERIFYHOST , 0);
//        curl_setopt($ch , CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1);
//        curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , false);
//        curl_setopt($ch , CURLOPT_POSTFIELDS , $encodedData);
//
//        $result = curl_exec($ch);
//
//        if ($result === FALSE){
//            return HelperFunction::ServerErrorResponse();
//        }
//
//        curl_close($ch);
//
//
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
            return HelperFunction::ServerErrorResponse();
        }
    }
}
