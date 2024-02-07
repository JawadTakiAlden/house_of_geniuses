<?php

namespace App\Http\Controllers;

use App\Models\ExportableFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportableFileController extends Controller
{
    public function getAllFiles()
    {
        $files = ExportableFile::all();
        return response()->json($files);
    }

    public function downloadFile($fileName)
    {
        $file = ExportableFile::where('path', $fileName)->first();
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $filePath = 'excel_files/' . $fileName;
        return Storage::download($filePath);
    }

    public function deleteFile($fileName)
    {
        $file = ExportableFile::where('path', $fileName)->first();
        if (!$file) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $filePath = 'excel_files/' . $fileName;
        Storage::delete($filePath);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}
