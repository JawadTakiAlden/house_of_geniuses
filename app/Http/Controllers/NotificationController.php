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
        $messaging = $firebase->createMessaging();

        return $FcmToken;
//        $notification = Notification::create($title, $body);
        $notification = Notification::create($title, $body);
//        $result = null;

//        $chunks = array_chunk($FcmToken, 500);

        $message = CloudMessage::withTarget('token' , $FcmToken)
            ->withNotification($notification);
        $report = $messaging->send($message);
//        $reporst = collect();
//        foreach ($chunks as $chunk) {
//            // Create the notification
//
//            try {
//                // Send the message as a multicast
//
//                $reporst->push($report);
//            } catch (\Exception $e) {
//                Log::error('Failed to send multicast notification: ' . $e->getMessage());
//            }
//        }
//        foreach ($FcmToken as $token) {
//            $message = CloudMessage::new();
//            $message->withNotification($notification);
//
//            try {
//                $result = $this->messaging->sendMulticast($message , $FcmToken);
//            } catch (\Exception $e) {
//                Log::error('Failed to send notification , request failed with message : '.$e->getMessage());
//            }
//        }
        return $this->success([$report , $FcmToken] ,  __('messages.notification_controller.send_successfully'));
    }

    public function sendNotificationForAllUser(SendNotificationRequest $request){
        try {
//            $tokens = User::whereNotNull('device_notification_id')->pluck('device_notification_id')->all();
//            $tokens = User::where('phone' , "0948966976")->first()->pluck('device_notification_id');
            $tokens = User::where("device_notification_id" , "!=" , null)->pluck("device_notification_id");
            return [
                "tokens" => $tokens[0]
            ];
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
