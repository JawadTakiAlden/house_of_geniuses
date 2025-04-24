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

class SendFirebaseNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $title;
    protected $body;
    protected $tokens;
    public function __construct($title, $body, $token)
    {
        $this->title = $title;
        $this->body = $body;
        $this->tokens = $token;
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

        foreach ($this->tokens as $token) {
            $message = CloudMessage::withTarget('token', $token)
                ->withNotification($notification);

            try {

                $messaging->send($message);

            } catch (\Exception $e) {
                Log::error("Failed to send notification to token {$token}: " . $e->getMessage());
            }
        }
    }
}
