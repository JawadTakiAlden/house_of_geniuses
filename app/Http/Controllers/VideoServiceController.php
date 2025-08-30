<?php

namespace App\Http\Controllers;

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
        $request->validate([
            'video' => 'required|file|mimes:mp4,mov,mkv|max:5120', // 5GB max
            'resolutions' => 'array', // optional resolutions
            'resolutions.*' => 'in:480p,720p,1080p'
        ]);

        $resolutions = $request->input('resolutions', ['720p']);

        $result = $this->videoService->uploadVideo(
            $request->file('video'),
            $resolutions
        );

        return response()->json($result);
    }
}
