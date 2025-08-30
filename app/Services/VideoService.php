<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class VideoService
{
    protected string $apiUrl;
    protected string $clientId;
    protected string $clientSecret;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->apiUrl = env("VIDEO_SERVICE_API_URL");
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    /**
     * Fetch all videos
     */
    public function getAllVideos(): array
    {
        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-client-secret' => $this->clientSecret,
        ])->get($this->apiUrl . '/api/videos');

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }

    /**
     * Fetch single video by ID
     */
    public function getVideo(string $videoId): array
    {
        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-client-secret' => $this->clientSecret,
        ])->get($this->apiUrl . '/api/videos/' . $videoId);

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }

    /**
     * Upload a video file to Node.js service
     * $file: instance of UploadedFile
     * $resolutions: optional array like ['480p', '720p']
     */
    public function uploadVideo(UploadedFile $file, array $resolutions = ['480p', '720p', '1080p']): array
    {
        $response = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-client-secret' => $this->clientSecret,
        ])->attach(
                'video',
                fopen($file->getRealPath(), 'r'),
                $file->getClientOriginalName()

            )->post($this->apiUrl . '/api/videos', [
                    'resolutions' => $resolutions
                ]);

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }
}
