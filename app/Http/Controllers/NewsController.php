<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Resources\NewsResource;
use App\HttpResponse\HTTPResponse;
use App\Models\News;
use App\Http\Requests\StoreNewsRequest;

class NewsController extends Controller
{
    use HTTPResponse;
    public function index()
    {
        try {
            $news = News::orderBy('position')->get();
            return $this->success(NewsResource::collection($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function visibleNews(){
        try {
            $news = News::where('is_visible' , true)->orderBy('position')->get();
            return $this->success(NewsResource::collection($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
    public function store(StoreNewsRequest $request)
    {
        try {
            $news = News::create($request->only(['is_visible' , 'image' , 'title']));
            return NewsResource::make($news);
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function destroy($newsID)
    {
        $news = HelperFunction::getNewsByID($newsID);
        if (!$news){
            return $this->error(trans('messages.news_not_found') ,404);
        }
        try {
            $news->delete();
            return $this->success(NewsResource::make($news) , trans('messages.delete_news'));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function show($newsID){
        try {
            $news = HelperFunction::getNewsByID($newsID);
            if (!$news){
                return $this->error(trans('messages.news_not_found') ,404);
            }
            return $this->success(NewsResource::make($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function switchVisibility($newsID){
        $news = HelperFunction::getNewsByID($newsID);
        if (!$news){
            return $this->error(trans('messages.news_not_found') ,404);
        }
        try {
            $news->update([
                'is_visible' => !$news->is_visible
            ]);
            return $this->success(NewsResource::make($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
}
