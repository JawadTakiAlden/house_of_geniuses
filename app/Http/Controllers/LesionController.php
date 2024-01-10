<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Resources\LesionResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Lesion;
use App\Http\Requests\StoreLesionRequest;
use App\Http\Requests\UpdateLesionRequest;

class LesionController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }

    public function getAll($chpaterID){
        try {
            $chapter = HelperFunction::getChapterByID($chpaterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $lesions = Lesion::where('chapter_id' , $chpaterID)->get();
            return $this->success(LesionResource::collection($lesions));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function getVisible($chpaterID){
        try {
            $chapter = HelperFunction::getChapterByID($chpaterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $lesions = Lesion::where('chapter_id' , $chpaterID)->where('is_visible' , true)->get();
            return $this->success(LesionResource::collection($lesions));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function switchVisibility($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion dose\'nt found in our system' , 404);
            }
            $lesion->update([
               'is_visible' => !$lesion->is_visible
            ]);
            return $this->success(
                LesionResource::make($lesion) ,
                $lesion->title . ' changed to be ' . $lesion->is_visible ? 'visible' : 'invisible' . ' successfully'
            );
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function delete($lesionID){
        try {
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion dose\'nt found in our system' , 404);
            }
            $lesion->delete();
            return $this->success(
                LesionResource::make($lesion) ,
                $lesion->title . ' deleted successfully'
            );
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function store(StoreLesionRequest $request){
        try {
            $request->validated($request->only(['title' , 'link' , 'is_open' , 'is_visible' , 'chapter_id' , 'time']));
            $lesion = Lesion::create($request->only(['title' , 'link' , 'is_open' , 'is_visible' , 'chapter_id' , 'time']));
            return $this->success(LesionResource::make($lesion) , $lesion->title . ' added successfully to ' . $lesion->chapter->name . ' in ' . $lesion->chapter->course->name .' course');
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function update(UpdateLesionRequest $request , $lesionID){
        try {
            $request->validated($request->only(['title' , 'link' , 'is_open' , 'is_visible' , 'chapter_id']));
            $lesion = HelperFunction::getLesionByID($lesionID);
            if (!$lesion){
                return $this->error('lesion dose\'nt found in our system' , 404);
            }
            $lesion->update($request->only(['title' , 'link' , 'is_open' , 'is_visible' , 'chapter_id']));
            return $this->success(LesionResource::make($lesion) , $lesion->title . ' updated successfully');
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }
}
