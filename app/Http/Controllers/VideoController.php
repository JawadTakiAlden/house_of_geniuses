<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
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
        $this->client = new Vimeo("0c54dc9c31e4fd73ce55e96e811adabfe2464e65"
            , "5PKi7N4vdDfxa+ItVblHQCIxz93hncjmASvTa5RYSr63/BzkpvlTdRhYrasqOMDCxDHxSnAgREh06MvtGPRrD/oFVpLd8AkYXCcTPg8yeRtmcISS8t4wXHb3DtYJQEdy",
            "51036c9679962cd9dd9642a5f85f6ca9");
    }

    public function getVideos () {
        try {
            $queryParams = [];
            if (\request('link')) {
                $link = \request('link');
                $link = strtok($link, '?');
                $queryParams['query'] = $link;
            }
            $response = $this->client->request('/users/216130188/videos',$queryParams, 'GET');
            $responseData = $response['body'];
            $videos = $responseData['data'];
            $test = collect($videos);
            return $this->success(VideoResource::collection($test));
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function watch(){
        try {
            if (!\request('link')){
                return $this->error(__('messages.video_controller.link_not_correct') , 500);
            }
            $video = $this->client->request(\request('link').'?fields=play');
            $data = $video['body'];
            $data = $data['play'];
            $data = $data['progressive'];
            return response([
                'link' => $data
            ] , 200);
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function download(){
        try {
            if (!\request('link')){
                return $this->error(__('messages.video_controller.link_not_correct') , 500);
            }
            $video = $this->client->request(\request('link').'?fields=download');
            $downloadArray = $video['body'];
            return response([
                'link' => $downloadArray
            ] , 200);
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }
}
