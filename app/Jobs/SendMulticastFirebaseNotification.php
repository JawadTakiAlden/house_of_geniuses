<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendMulticastFirebaseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $title;
    protected $body;
    protected $FcmTokens;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $body, array $FcmTokens)
    {
        $this->title = $title;
        $this->body = $body;
        $this->FcmTokens = $FcmTokens;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $firebase = (new Factory())
            ->withServiceAccount(config_path('firebase_config.json'));
        $messaging = $firebase->createMessaging();

        $notification = Notification::create($this->title, $this->body);

        $message = CloudMessage::new()->withNotification($notification);

        try {
            // Send multicast message to all tokens in one request
            $response = $messaging->sendMulticast($message, $this->FcmTokens);

            // Handle failures
            if ($response->hasFailures()) {
                foreach ($response->failures()->getItems() as $failure) {
                    Log::error('Failed to send to token: ' . $failure->target()->value() . ' due to ' . $failure->error()->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send notification, request failed with message: ' . $e->getMessage());
        }
    }
}
