<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;

class VideoService
{
    protected string $apiUrl;
    protected string $clientId;
    protected string $clientSecret;

    protected PendingRequest $request;

    public function __construct(string $clientId, string $clientSecret)
    {
        $this->apiUrl = env("VIDEO_SERVICE_API_URL");
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->request = Http::withHeaders([
            'x-client-id' => $this->clientId,
            'x-client-secret' => $this->clientSecret,
        ]);
    }

    /**
     * Fetch all videos
     */
    public function getAllVideos(): array
    {
        $response = $this->request->get($this->apiUrl . '/api/videos');

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
        $response = $this->request->get($this->apiUrl . '/api/videos/' . $videoId);

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }


    public function getFolders(): array
    {
        $response = $this->request->get($this->apiUrl . '/api/folders');

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }
    public function getFoldersTree(): array
    {
        $response = $this->request->get($this->apiUrl . '/api/foldersTree');

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }


    public function showFolder(string $folder_Id): array
    {
        $response = $this->request->get($this->apiUrl . '/api/folders/' . $folder_Id);

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }

    public function showFolderTree(string $folder_Id): array
    {
        $response = $this->request->get($this->apiUrl . '/api/folders/' . $folder_Id . "/tree");

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }


    public function deleteFolder(string $folder_Id): array
    {
        $response = $this->request->delete($this->apiUrl . '/api/folders/' . $folder_Id);

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->body(),
            ];
        }

        return $response->json();
    }




    public function createFolders(array $data): array
    {
        $response = $this->request->post($this->apiUrl . '/api/folders', [
            "parent_folder_id" => $data["parent_folder_id"],
            "name" => $data["name"]
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



    public function uploadVideo(
        UploadedFile $file,
        string $folderId,
        string $description,
        array $resolutions = ['480p', '720p', '1080p']
    ): array {
        $fileStream = fopen($file->getRealPath(), 'r');
        $fileSize = filesize($file->getRealPath());

        $response = $this->request->withHeaders([
            'x-description' => json_encode($description),
            'x-folder-id' => $folderId,
            'x-resolutions' => json_encode($resolutions),
            'Content-Length' => $fileSize,
        ])
            ->attach('video', $fileStream, $file->getClientOriginalName())
            ->post($this->apiUrl . '/api/videos');

        fclose($fileStream);

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
