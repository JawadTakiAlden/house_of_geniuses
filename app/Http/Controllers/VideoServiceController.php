<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoUploadRequest;
use App\Services\EncryptionService;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoServiceController extends Controller
{
    protected VideoService $videoService;
    protected EncryptionService $encryptionService;


    public function __construct()
    {
        $this->videoService = new VideoService(
            env('VIDEO_SERVICE_CLIENT_ID'),
            env('VIDEO_SERVICE_CLIENT_SECRET')
        );

        $this->encryptionService = new EncryptionService();
    }

    /**
     * GET /api/videos
     * Fetch all videos
     */
    public function index()
    {
        $videos = $this->videoService->getAllVideos();
        return response()->json($videos);
    }

    /**
     * GET /api/videos/{id}
     * Fetch single video by ID
     */
    public function show(Request $request, string $id)
    {
        $clientPublicKey = $request->input('public_key');

        $video = $this->videoService->getVideo($id);
        $encryptedData = $this->encryptionService->encryptDataForClient($video, $clientPublicKey);
        return response()->json($encryptedData);
    }

    /**
     * POST /api/videos
     * Upload a video
     */
    public function upload(VideoUploadRequest $request)
    {
        try {

            if (!$request->hasFile('video')) {

                return response()->json(['error' => 'No video file uploaded'], 400);
            }

            if (!$request->file('video')->isValid()) {
                return response()->json(['error' => 'Uploaded file is not valid'], 400);
            }

            $resolutions = $request->input('resolutions', ['720p']);

            $result = $this->videoService->uploadVideo(
                $request->file('video'),
                $request->folder_id,
                $request->description,
                $resolutions
            );

            return response()->json($result);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
