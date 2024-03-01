<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\HttpResponse\HTTPResponse;
use Illuminate\Http\Request;
use Vimeo\Vimeo;

class VideoController extends Controller
{
    use HTTPResponse;

    private Vimeo $client;

    public function __construct()
    {
        $this->client = new Vimeo("1a223f522e28ec1fb6b9e8e25f088348ecf1a6ef"
            , "xEjIb7Q0J2zYJRfsgpb1XRcwG2XRig/Nm5Gr3nejJMuFuuLGxr1lx0Z2A7kHN8MOvMPHhLG+pOX5fI5bk7WC5YIvQsPpv+9/pM2a8UlyqQOCfg7VqtGRZ9qtlHcOUH3t",
            "b666b813b6109e0574302a8d4237445a");
    }

    public function getVideos () {
        try {
            $response = $this->client->request('/users/216130188/videos', array(), 'GET');
            $responseData = $response['body'];
            $videos = $responseData['data'];
            $test = collect($videos);
            return $this->success(VideoResource::collection($test));
        }catch (\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function watch($videoID){
        $video = $this->client->request($videoID.'?fields=play');
        $data = $video['body'];
        $data = $data['play'];
        $data = $data['hls'];
        $link = $data['link'];
        return response([
            'link' => $link
        ] , 200);
    }

    public function download($videoID){
        $video = $this->client->request($videoID.'?fields=download');
        $data = collect($video['body'])->link;
//        $data = collect($video['download'])->map(fn($download) =>
//            [
//                'link' => $download['link'],
//                'type' => $download['type'],
//                'rendition' => $download['rendition'],
//                'size_short' => $download['size_short'],
//
//            ]
//        );
        return response([
            'link' => $data
        ] , 200);
    }
}
