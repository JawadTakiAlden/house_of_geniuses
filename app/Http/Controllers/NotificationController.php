<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\SendNotificationRequest;
use App\HttpResponse\HTTPResponse;
use App\Jobs\SendFirebaseNotificationJob;
use App\Jobs\SendMulticastFirebaseNotification;
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
        $chunks = array_chunk($FcmToken, 200);

        foreach ($chunks as $chunk) {
            dispatch(new SendMulticastFirebaseNotification($title, $body, $chunk));
        }
//        foreach ($FcmToken as $token) {
//            dispatch(new SendFirebaseNotificationJob($title , $body , $token));
//        }
        return $this->success(null ,  __('messages.notification_controller.send_successfully'));
    }

    public function sendNotificationForAllUser(SendNotificationRequest $request){
        try {
            $tokens = User::where('device_notification_id' , "!=" , null)->pluck('device_notification_id');
            return $tokens->toArray();
            $result = $this->BasicSendNotification($request->title , $request->body , $tokens->toArray());
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
