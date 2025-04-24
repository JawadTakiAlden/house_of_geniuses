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
        $firebase = (new Factory())
            ->withServiceAccount(storage_path('app/firebase/firebase_config.json'));

        $messaging = $firebase->createMessaging();

        $notification = Notification::fromArray([
            'title' => $this->title,
            'body' => $this->body,
        ]);

        $message = CloudMessage::new();

        $message = $message->withNotification($notification);

        $messaging->sendMulticast($message, $this->tokens);
    }
}
