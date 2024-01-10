<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\CourseResource;
use App\Http\Resources\UserResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Course;
use App\Models\User;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    use HTTPResponse;

    private function permissionDenide(){
        return $this->error(trans('messages.permission_denied') , 403);
    }

    public function profile(){
        try {
            return $this->success(UserResource::make(Auth::user()));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function getUserProfile($userID){
        try {
            $user = HelperFunction::getUserById($userID);
            if (!$user){
                return $this->error(trans('messages.user_not_found') , 404);
            }
            if (Gate::denies('get_profile_of_user' , $user)){
                return $this->permissionDenide();
            }
            return $this->success(UserResource::make($user));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function getTeacher(){
        try {
            $teacher = User::where('type' , UserType::TEACHER)->get();
            return $this->success(UserResource::collection($teacher));
        }catch (\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function create(CreateUserRequest $request){
        $request->validated($request->only(['email', 'full_name', 'phone', 'password', 'type']));
        try {
            $user = User::create($request->only(['email', 'full_name', 'phone', 'password', 'type']));
            return $this->success(UserResource::make($user));
        }catch (\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function updateProfile(UpdateUserRequest $request , $userID){
        $user = HelperFunction::getUserById($userID);
        if (!$user){
            return $this->error(trans('messages.user_not_found') , 404);
        }
        if (!Gate::allows('update_profile' , $user)){
            return $this->permissionDenide();
        }
        $request->validated($request->only('full_name' , 'phone' , 'email'));
        try {
            $user->update($request->only('full_name', 'phone' , 'email'));
            return $this->success(UserResource::make($user));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }

    }


//    public function blockAccount($userID){
//        try {
//            $user = HelperFunction::getUserById($userID);
//            if (!$user){
//                return $this->error(trans('messages.user_not_found') , 404);
//            }
//            $user->update([
//                'is_blocked' => true,
//            ]);
//            return $this->success(UserResource::make($user), $user->full_name . ' blocked successfully');
//        }catch(\Throwable $th){
//            return $this->error($th->getMessage() , 500);
//        }
//
//    }

//    public function unBlockAccount($userID){
//        $user = HelperFunction::getUserById($userID);
//        if (!$user){
//            return $this->error('user you\'re trying to unblock , not found in our system' , 404);
//        }
//        try {
//            $user->update([
//                'is_blocked' => false,
//            ]);
//
//            return $this->success(UserResource::make($user) , $user->full_name . ' unblocked successfully');
//        }catch(\Throwable $th){
//            return $this->error($th->getMessage() , 500);
//        }
//    }

    public function switchBlockState($userID) {
        try {
            $user = HelperFunction::getUserById($userID);
            if (!$user){
                return $this->error(trans('messages.user_not_found') , 404);
            }
            if ($user->is_blocked === 'false'){
                $user->currentAccessToken()->delete();
            }

            $user->update([
                'is_blocked' =>  !$user->is_blocked
            ]);

            return $this->success(UserResource::make($user), trans('messages.user_blocked' , [
                'block_state' => boolval($user->is_blocked) ? trans('messages.block_word') : trans('unblock_word')
            ]));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }

    }

    public function getAllUser(){
        try {
            $users = User::where('type' , UserType::STUDENT)->get();
            return $this->success(UserResource::collection($users));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }


    public function getAllBlockedUser(){
        try {
            $users = User::where('type' , 'student')->where('is_blocked' , true)->get();
            return $this->success(UserResource::collection($users));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }

    }

    public function GetAllInrolnmentCourseForThis($userID){
        $user = HelperFunction::getUserById($userID);
        if (!$user){
            return $this->error(trans('messages.user_not_found') , 404);
        }
        try {
            $courses = Course::whereHas('accountInrolments' , fn($query) =>
            $query->where('user_id' , $user->id)
            )->get();
            return $this->success(CourseResource::collection($courses));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function getAllUserThatSignInToThis($courseID){
        $course = HelperFunction::getCourseByID($courseID);
        if (!$course){
            return $this->error(trans('messages.course_not_found') , 404);
        }
        try {
            $users = User::whereHas('accountInrolments' , fn($query) =>
            $query->where('course_id' , $course->id)
            )->get();
            return $this->success(UserResource::collection($users));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }

    public function inrollnmentCourseOfUser($userID){
        try {
            $user = HelperFunction::getUserById($userID , ['inroledCorurses']);
            if (!$user){
                return $this->error(trans(trans('messages.user_not_found')) , 404);
            }
            if (Gate::denies('get_courses_of_user' , $user)){
                return $this->permissionDenide();
            }
            return $this->success(CourseResource::collection($user->inroledCorurses));
        }catch(\Throwable $th){
            return $this->error($th->getMessage() , 500);
        }
    }
}
