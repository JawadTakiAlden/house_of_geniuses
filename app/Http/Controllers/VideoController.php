<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vimeo\Vimeo;

class VideoController extends Controller
{


    public function getVideos () {
        $client = new Vimeo("f5558ea3eb98817fbe2126ae2541b6e11bfb0e44"
            , "bXD6qM5hnj79bWmodVz7vIw4ZS1maOBgVUl8N5cWnV2r6aTMZ6QNI3UT5LwW+lOSLRH2vvfoE1xoAuKfsjNbe+pEjXwaSXt+7XCPxaJzNWQJi/GWUbkd3o4DcM307fP9",
            "0090c4cf290951714e846847fe8b2fe5");

        $response = $client->request('/me/videos', array(), 'GET');
        $responseData = $response['body'];
        $categories = $responseData['data'];
        return $categories;
    }

    public function watch($videoID){
        $client = new Vimeo("f5558ea3eb98817fbe2126ae2541b6e11bfb0e44"
            , "bXD6qM5hnj79bWmodVz7vIw4ZS1maOBgVUl8N5cWnV2r6aTMZ6QNI3UT5LwW+lOSLRH2vvfoE1xoAuKfsjNbe+pEjXwaSXt+7XCPxaJzNWQJi/GWUbkd3o4DcM307fP9",
            "0090c4cf290951714e846847fe8b2fe5");
        // Make a request to Vimeo API to get video details
        $video = $client->request($videoID);

        // Extract necessary data from the video response
        $videoUrl = 'test';
        $videoTitle = 'test';

        // Return HTML page with video player
        return view('video.watch', compact('videoUrl', 'videoTitle'));
    }
}
