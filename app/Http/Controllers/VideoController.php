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
        $client = new Vimeo("f5558ea3eb98817fbe2126ae2541b6e11bfb0e44"
            , "bXD6qM5hnj79bWmodVz7vIw4ZS1maOBgVUl8N5cWnV2r6aTMZ6QNI3UT5LwW+lOSLRH2vvfoE1xoAuKfsjNbe+pEjXwaSXt+7XCPxaJzNWQJi/GWUbkd3o4DcM307fP9",
            "0090c4cf290951714e846847fe8b2fe5");

        $response = $client->request('/me/videos', array(), 'GET');
        $responseData = $response['body'];
        $videos = $responseData['data'];
        return $this->success(VideoResource::collection($videos));
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
