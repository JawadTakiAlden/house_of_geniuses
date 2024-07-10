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

    private Vimeo $client1;
    private Vimeo $client2;

    public function __construct()
    {
        $this->client1 = new Vimeo(env('VIMEO_CLIENT_ID')
            , env('VIMEO_CLIENT_SECRET'),
            env('VIMEO_ACCESS_TOKEN'));
        $this->client2 = new Vimeo(env('VIMEO_CLIENT_ID2')
            , env('VIMEO_CLIENT_SECRE2'),
            env('VIMEO_ACCESS_TOKEN2'));
    }

    public function getVideos () {
        try {
            $queryParams = [];
            if (\request('link')) {
                $link = \request('link');
                $link = strtok($link, '?');
                $queryParams['query'] = $link;
            }
            $response1 = $this->client1->request('/users/216130188/videos',$queryParams, 'GET');
            $response2 = $this->client2->request('/users/222393454/videos',$queryParams, 'GET');
            $responseData1 = $response1['body'];
            $videos1 = $responseData1['data'];

            $responseData2 = $response2['body'];
            $videos2 = $responseData2['data'];
            $test1 = collect($videos1);
            $test2 = collect($videos2);
            $finalData = $test1->merge($test2);
            return $this->success(VideoResource::collection($finalData));
        }catch (\Throwable $th){
            return $this->error($th->getMessage(),500);
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
