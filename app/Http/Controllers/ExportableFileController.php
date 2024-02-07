<?php

namespace App\Http\Controllers;

use App\HttpResponse\HTTPResponse;
use App\Models\ExportableFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExportableFileController extends Controller
{
    use HTTPResponse;
    public function getAllFiles()
    {
        $files = ExportableFile::all();
        return response()->json($files);
    }

    public function downloadFile($fileName)
    {
        $file = ExportableFile::where('path', $fileName)->first();
        if (!$file) {
            return $this->error('the requested file doesnt found in our system' , 404);
        }

        $filePath = 'excel_files/' . $fileName;
        return Storage::download($filePath);
    }

    public function deleteFile($fileName)
    {
        $file = ExportableFile::where('path', $fileName)->orderBy('created_at' , 'desc')->first();
        if (!$file) {
            return $this->error('the requested file doesnt found in our system' , 404);
        }
        ExportableFile::where('path' , $fileName)->delete();
        $filePath = 'excel_files/' . $fileName;
        Storage::delete($filePath);
        $file->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}
