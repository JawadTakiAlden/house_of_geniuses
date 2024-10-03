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
//        $tokenChunks = array_chunk($FcmToken, 500);

//        return $this->success($FcmToken);

        foreach ($FcmToken as $token) {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($message);
            try {
                $this->messaging->send($message);
            } catch (\Exception $e) {
                Log::error('Failed to send notification , request failed with message : '.$e->getMessage());
            }
        }

//        try {
//            $report = null;
//            foreach ($tokenChunks as $tokens) {
//                $report = $this->messaging->sendMulticast($message, $tokens);
//            }
//            return $this->success([$report , $FcmToken], __('messages.notification_controller.send_successfully'));
//        } catch (\Kreait\Firebase\Exception\MessagingException $e) {
//            return $this->error($e->getMessage() , 500);
//        } catch (FirebaseException $e) {
//            return $this->error($e->getMessage() , 500);
//        }
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
