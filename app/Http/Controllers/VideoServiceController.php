<?php

namespace App\Http\Controllers;

use App\Http\Requests\VideoUploadRequest;
use App\Services\VideoService;
use Illuminate\Http\Request;

class VideoServiceController extends Controller
{
    protected VideoService $videoService;

    public function __construct()
    {
        $this->videoService = new VideoService(
            env('VIDEO_SERVICE_CLIENT_ID'),
            env('VIDEO_SERVICE_CLIENT_SECRET')
        );
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
    public function show(string $id)
    {
        $video = $this->videoService->getVideo($id);
        return response()->json($video);
    }

    /**
     * POST /api/videos
     * Upload a video
     */
    public function upload(Request $request)
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
