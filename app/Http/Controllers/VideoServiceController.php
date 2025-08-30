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

        $resolutions = $request->input('resolutions', ['720p']);
        return resolutions;

        $result = $this->videoService->uploadVideo(
            $request->file("video"),
            $resolutions
        );

        return response()->json($result);
    }
}
