<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\HttpResponse\HTTPResponse;
use App\Models\News;
use App\Http\Requests\StoreNewsRequest;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    use HTTPResponse;
    public function index()
    {
        try {
            $news = News::orderBy('position' , 'desc')->orderBy('position_update' , 'desc')->get();
            return $this->success(NewsResource::collection($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function visibleNews(){
        try {
            $news = News::where('is_visible' , true)->orderBy('position' , 'desc')->orderBy('position_update', 'desc')->get();
            return $this->success(NewsResource::collection($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
    public function store(StoreNewsRequest $request)
    {
        try {
            $news = News::create($request->only(['is_visible' , 'image' , 'title']));
            return $this->success(NewsResource::make($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function update(UpdateNewsRequest $request , $newsID){
        {
            try {
                $news = HelperFunction::getNewsByID($newsID);
                if (!$news){
                    return $this->error(trans('messages.news_not_found') ,404);
                }
                if ($request->position){
                    if (intval($request->position) !== intval($news->position)){
                        $news->update(array_merge($request->only(['title' , 'position' , 'is_visible' , 'image']) , ['position_update' => DB::raw('CURRENT_TIMESTAMP')]));
                        return $this->success(NewsResource::make($news));
                    }else{
                        $news->update($request->only(['title' , 'is_visible' , 'image']));
                        return $this->success(NewsResource::make($news));
                    }
                }
                $news->update($request->only(['title' , 'is_visible' , 'image']));
                return $this->success(NewsResource::make($news));
            }catch(\Throwable $th){
                return $this->error($th->getMessage() , 500);
            }
        }
    }

    public function destroy($newsID)
    {

        try {
            $news = HelperFunction::getNewsByID($newsID);
            if (!$news){
                return $this->error(trans('messages.news_not_found') ,404);
            }
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
        try {
            $news = HelperFunction::getNewsByID($newsID);
            if (!$news){
                return $this->error(trans('messages.news_not_found') ,404);
            }
            $news->update([
                'is_visible' => !$news->is_visible
            ]);
            return $this->success(NewsResource::make($news));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
}
