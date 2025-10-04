<?php

namespace App\Http\Controllers;

use App\Http\Requests\FolderRequest;
use App\Services\VideoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FolderController extends Controller
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
     * Display a listing of folders
     */
    public function index()
    {
        $response = $this->videoService->getFolders();

        if (isset($response['success']) && $response['success'] === false) {
            return response()->json([
                'message' => 'Failed to fetch folders',
                'error' => $response['message'] ?? 'Unknown error',
            ], $response['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response['folders'] ?? $response,
        ]);
    }


    public function indexTree()
    {
        $response = $this->videoService->getFoldersTree();

        if (isset($response['success']) && $response['success'] === false) {
            return response()->json([
                'message' => 'Failed to fetch folders',
                'error' => $response['message'] ?? 'Unknown error',
            ], $response['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response['folders'] ?? $response,
        ]);
    }

    /**
     * Display a specific folder with its nested subfolders and videos (tree)
     */
    public function show($folderId)
    {
        $response = $this->videoService->showFolderTree($folderId);

        if (isset($response['success']) && $response['success'] === false) {
            return response()->json([
                'message' => 'Failed to fetch folder tree',
                'error' => $response['message'] ?? 'Unknown error',
            ], $response['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response,
        ]);
    }

    /**
     * Create a new folder
     */
    public function create(FolderRequest $request)
    {
        $data = $request->validated();

        $response = $this->videoService->createFolders([
            'parent_folder_id' => $data['parent_folder_id'] ?? null,
            'name' => $data['name'],
        ]);

        if (isset($response['success']) && $response['success'] === false) {
            return response()->json([
                'message' => 'Failed to create folder',
                'error' => $response['message'] ?? 'Unknown error',
            ], $response['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response,
        ], 201);
    }

    /**
     * Update folder details
     */
    public function update(FolderRequest $request, $folderId)
    {
        $data = $request->validated();

        // Since the Node.js API doesn’t have an update route yet, we can simulate via delete + recreate or skip
        // Here we'll log a warning until the API supports update
        Log::warning("Update folder not yet implemented in external video service", [
            'folder_id' => $folderId,
            'data' => $data,
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Folder update not implemented in the Video Service',
        ], 501);
    }

    /**
     * Delete a folder
     */
    public function delete($folderId)
    {
        $response = $this->videoService->deleteFolder($folderId);

        if (isset($response['success']) && $response['success'] === false) {
            return response()->json([
                'message' => 'Failed to delete folder',
                'error' => $response['message'] ?? 'Unknown error',
            ], $response['status'] ?? 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Folder deleted successfully',
        ]);
    }
}
