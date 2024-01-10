<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Resources\ChapterResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Chapter;
use App\Http\Requests\StoreChapterRequest;
use App\Http\Requests\UpdateChapterRequest;

class ChapterController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
    public function getAll($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $chapters = Chapter::where('course_id' , $courseID)->get();
            return $this->success(ChapterResource::collection($chapters));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function getVisible($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $chapters = Chapter::where('course_id' , $courseID)->where('is_visible' , true)->get();
            return $this->success(ChapterResource::collection($chapters));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }
    public function switchVisibility($chapterID) {
        try {
            $chapter = HelperFunction::getChapterByID($chapterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $chapter->update([
               'is_visible' => !$chapter->is_visible
            ]);
            return $this->success(ChapterResource::make($chapter)
                , 'chapter change to be ' .
                $chapter->is_visible ? 'visible' : 'invisible' . ' successfully'
            );
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function destroy($chapterID){
        try {
            $chapter = HelperFunction::getChapterByID($chapterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $chapter->delete();
            return $this->success(ChapterResource::make($chapter)
                , 'chapter deleted successfully'
            );
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function store(StoreChapterRequest $request){
        $request->validated($request->only('name' , 'course_id' , 'is_visible'));
        try {
            $course = HelperFunction::getCourseByID(intval($request->course_id));
            if (!$course){
                return $this->error('course does\'nt found in our system' , 404);
            }
            $chapter = Chapter::create($request->only('name' , 'course_id' , 'is_visible'));
            return $this->success(ChapterResource::make($chapter) , $chapter->name . ' added successfully to ' . $chapter->course->name);
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function update(UpdateChapterRequest $request , $chapterID){
        $request->validated($request->only(['name' , 'is_visible']));
        try {
            $chapter = HelperFunction::getChapterByID($chapterID);
            if (!$chapter){
                return $this->error('chapter dose\'nt found in our system' , 404);
            }
            $chapter->update($request->only(['name' , 'is_visible']));
            return $this->success(ChapterResource::make($chapter) , $chapter->name . ' updated successfully');
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

}
