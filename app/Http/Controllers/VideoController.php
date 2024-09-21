<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\WatchVideoRequest;
use App\Http\Resources\VideoResource;
use App\HttpResponse\HTTPResponse;
use Illuminate\Http\Request;
use Vimeo\Vimeo;
use Google\Client;
use Google\Service\YouTube;

class VideoController extends Controller
{
    use HTTPResponse;

    private Vimeo $client1;
    private Vimeo $client2;
    protected $youtubeClient;
    protected $youtube;
    public function __construct()
    {
        $this->client1 = new Vimeo(env('VIMEO_CLIENT_ID')
            , env('VIMEO_CLIENT_SECRET'),
            env('VIMEO_ACCESS_TOKEN'));
        $this->client2 = new Vimeo(env('VIMEO_CLIENT_ID_TWO')
            , env('VIMEO_CLIENT_SECRET_TWO'),
            env('VIMEO_ACCESS_TOKEN_TWO'));

        $this->client = new Client();

        $this->client->setDeveloperKey(env('YOUTUBE_API_KEY'));
        $this->client->setApplicationName('House Of Geniuses YouTube API');

        $this->youtube = new YouTube($this->client);
    }

    public function getVideos () {
        try {
            $queryParams = [];
            if (\request('link')) {
                $link = \request('link');
                $link = strtok($link, '?');
                $queryParams['query'] = $link;
            }
            if (\request('source') === 'vimeo-1'){
                $response = $this->client1->request('/users/216130188/videos',$queryParams);
                $responseData = $response['body'];
                $videos = $responseData['data'];
                return $this->success(VideoResource::collection($videos));
            }else if (\request('source') === 'vimeo-2') {
                $response = $this->client2->request('/users/222393454/videos',$queryParams);
                $responseData = $response['body'];
                $videos = $responseData['data'];
                return $this->success(VideoResource::collection($videos));
            } else if (\request('source') === 'youtube') {
                return $this->success([] , 'from youtube');
            }else{
                return $this->error('you are provide unsupported platform to get videos' , 422);
            }

        }catch (\Throwable $th){
//            return HelperFunction::ServerErrorResponse();
            return $this->error($th->getMessage() , 500);
        }
    }

    public function watch(WatchVideoRequest $request){
        try {
            if (!$request->link){
                return $this->error(__('messages.video_controller.link_not_correct') , 422);
            }
            if ($request->source === 'vimeo-1'){
                $response = $this->client1->request(\request('link').'?fields=play');
                return $response['body']->error;
                return $this->success($this->watchLinkTransformer($response));
            }else if ($request->source === 'vimeo-2'){
                $response = $this->client2->request(\request('link').'?fields=play');
                if ($response['status'] == 200){
                    return $this->success($this->watchLinkTransformer($response));
                }else{
                    return $this->error($response['message'] , $response['status']);
                }

            }
        }catch (\Throwable $th){
//            return HelperFunction::ServerErrorResponse();
            return $this->error($th->getMessage() , 500);
        }
    }

    public function searchVideosByTitle()
    {
        $response = $this->youtube->search->listSearch('snippet', [
            'q' => \request('title'),
            'type' => 'video',
        ]);

        return $this->success($response);
    }

    private function watchLinkTransformer($videoResponse){
        $data = $videoResponse['body'];
        $data = $data['play'];
        $data = $data['progressive'];
        return [
            'link' => $data
        ];
    }
    private function donwloadLinkTransformer($videoResponse){
        $downloadArray = $videoResponse['body'];
        return [
            'link' => $downloadArray
        ];
    }
    public function download(){
        try {
            if (!\request('link')){
                return $this->error(__('messages.video_controller.link_not_correct') , 500);
            }
            if (\request('source') === 'vimeo-1'){
                $response = $this->client1->request(\request('link').'?fields=download');
                if (intval($response['status']) === 200){
                    return $this->success($this->donwloadLinkTransformer($response));
                }
            }else if (\request('source') === 'vimeo-2'){
                $response = $this->client2->request(\request('link').'?fields=download');
                if (intval($response['status']) === 200){
                    return $this->success($this->donwloadLinkTransformer($response));
                }
            }
        }catch (\Throwable $th){
            return HelperFunction::ServerErrorResponse();
        }
    }
}
