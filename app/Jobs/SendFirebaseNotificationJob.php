<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $title;
    protected $body;
    protected $tokens;
    public function __construct($title, $body, $tokens)
    {
        $this->title = $title;
        $this->body = $body;
        $this->tokens = $tokens;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $firebase = (new Factory())
                ->withServiceAccount(storage_path('app/firebase/firebase_config.json'));



            $messaging = $firebase->createMessaging();

            $notification = Notification::fromArray([
                'title' => $this->title,
                'body' => $this->body,
            ]);

            $message = CloudMessage::new()->withNotification($notification);

            $messaging->sendMulticast($message, $this->tokens);

            Log::channel('firebase')->info('Notification sent successfully', [
                'title' => $this->title,
                'body' => $this->body,
                'tokens' => $this->tokens,
            ]);
        } catch (\Throwable $e) {
            Log::channel('firebase')->error('Firebase notification failed', [
                'error' => $e->getMessage(),
            ]);
        }

    }
}
