<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\SendNotificationRequest;
use App\HttpResponse\HTTPResponse;
use App\Jobs\SendFirebaseNotificationJob;
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
//        $firebase = (new Factory())
//            ->withServiceAccount(config_path('firebase_config.json'));
//        $messaging = $firebase->createMessaging();
//
//        $notification = Notification::create($title, $body);
//
//        foreach ($FcmToken as $token) {
//            $message = CloudMessage::withTarget('token', $token)
//                ->withNotification($notification);
//            try {
//                $messaging->send($message);
//            } catch (\Exception $e) {
//                Log::error('Failed to send notification , request failed with message : '.$e->getMessage());
//            }
//        }
//        $firebase = (new Factory())
//            ->withServiceAccount(config_path('firebase_config.json'));
//        $messaging = $firebase->createMessaging();
//
//        $notification = Notification::create($title, $body);
//
//        $chunks = array_chunk($FcmToken, 500); // Firebase multicast supports max 500 tokens
//
//        foreach ($chunks as $chunk) {
//            $message = CloudMessage::new()
//                ->withNotification($notification)
//                ->withTarget('tokens', $chunk); // Send to a chunk of tokens
//
//            try {
//                $messaging->sendMulticast($message);
//            } catch (\Exception $e) {
//                Log::error('Failed to send notification , request failed with message : ' . $e->getMessage());
//            }
//        }

        foreach ($FcmToken as $token) {
            dispatch(new SendFirebaseNotificationJob($title, $body, $token));
        }
        return $this->success(null ,  __('messages.notification_controller.send_successfully'));
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
