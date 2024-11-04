<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function getImage($filepath , Request $request){
        $filePath = public_path($filepath);

        if (!file_exists($filePath)) {
            abort(404, 'Image not found.');
        }

        $scaleX = $request->query('scaleX', 1);
        $scaleY = $request->query('scaleY', 1);
        $height = $request->query('height', 0);
        $width = $request->query('width', 0);


        $img = Image::make($filePath);

        if ($width == null && $height == null){
            $width = $img->width() * $scaleX ;
            $height = $img->height() * $scaleY ;
        }

        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $img->response('jpg');
    }
}
