<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\StoreCourseValueRequest;
use App\Http\Requests\UpdateCourseValueRequest;
use App\Http\Resources\CourseResource;
use App\HttpResponse\HTTPResponse;
use App\Models\CourseValue;
use Illuminate\Http\Request;

class CourseValueController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
    public function store(StoreCourseValueRequest $request , $courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error(trans('messages.course_not_found'), 404);
            }
            $newCourseValue = CourseValue::create( array_merge($request->only(['value']), ['course_id' => $courseID]));
            return $this->success(CourseResource::make($newCourseValue->course) , trans('messages.create_course_value'));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function update(UpdateCourseValueRequest $request , $valueID){
        try {
            $value = HelperFunction::getCourseValueByID($valueID);
            if (!$value){
                return $this->error(trans('messages.value_not_found'), 404);
            }
            $value->update($request->only(['value']));
            return $this->success(CourseResource::make($value->course) , trans('messages.update_course_value'));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function destroy($valueID){
        try {
            $value = HelperFunction::getCourseValueByID($valueID);
            if (!$value){
                return $this->error(trans('messages.value_not_found'), 404);
            }
            $value->delete();
            return $this->success(CourseResource::make($value->course) , trans('messages.delete_course_value'));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }
}
