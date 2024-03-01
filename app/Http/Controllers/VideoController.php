<?php

namespace App\Http\Controllers;

use App\Http\Resources\VideoResource;
use App\HttpResponse\HTTPResponse;
use Illuminate\Http\Request;
use Vimeo\Vimeo;

class VideoController extends Controller
{
    use HTTPResponse;

    public function getVideos () {
        try {
            $client = new Vimeo("1a223f522e28ec1fb6b9e8e25f088348ecf1a6ef"
                , "xEjIb7Q0J2zYJRfsgpb1XRcwG2XRig/Nm5Gr3nejJMuFuuLGxr1lx0Z2A7kHN8MOvMPHhLG+pOX5fI5bk7WC5YIvQsPpv+9/pM2a8UlyqQOCfg7VqtGRZ9qtlHcOUH3t",
                "b666b813b6109e0574302a8d4237445a");

            $response = $client->request('/videos/917973997', array(), 'GET');
            $responseData = $response['body'];
            $videos = $responseData['data'];
            $test = collect($videos);
            return $test;
//            return $this->success(VideoResource::collection($test));
        }catch (\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function watch($videoID){
        $client = new Vimeo("f5558ea3eb98817fbe2126ae2541b6e11bfb0e44"
            , "bXD6qM5hnj79bWmodVz7vIw4ZS1maOBgVUl8N5cWnV2r6aTMZ6QNI3UT5LwW+lOSLRH2vvfoE1xoAuKfsjNbe+pEjXwaSXt+7XCPxaJzNWQJi/GWUbkd3o4DcM307fP9",
            "0090c4cf290951714e846847fe8b2fe5");
        $video = $client->request($videoID);
        $data = $video['body'];
        return view('video.watch', compact('data'));
    }
}
