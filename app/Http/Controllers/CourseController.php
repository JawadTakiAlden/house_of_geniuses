<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\SignInCourseRequest;
use App\Http\Resources\AllInrolledResource;
use App\Http\Resources\CourseInformationResource;
use App\Http\Resources\CourseResource;
use App\Http\Resources\InrolmentsResource;
use App\Http\Resources\ShowCourseInformationForAdmin;
use App\HttpResponse\HTTPResponse;
use App\Models\AccountInrolment;
use App\Models\ActivationCode;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\CourseCanActivated;
use App\Models\CourseCategory;
use App\Models\CourseTeacher;
use App\Models\CourseValue;
use App\Types\CodeType;
use App\Types\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    use HTTPResponse;

    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }

    public function getAllCourses(){
        try {
            $courses = Course::all();
            return $this->success(CourseResource::collection($courses));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function getAllIneolments(){
        try {
            $inrolments = AccountInrolment::with(['course', 'user', 'activationCode'])->orderByDesc('created_at')->get();
            return $this->success(AllInrolledResource::collection($inrolments));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function visibleCourses(){
        try {
            $courses = Course::where('is_visible' , true)->get();
            return $this->success(CourseResource::collection($courses));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }
    public function search() {
        try {
            $courses = Course::where('is_visible' , true)->filter(request(['search']))->get();
            return CourseResource::collection($courses);
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }
    public function getVisibleCourses($categoryID){
        try {
            $category = HelperFunction::getCategoryByID($categoryID , ['courses']);
            if (!$category){
                return $this->error(trans('messages.category_not_found'), 404);
            }
            return $this->success(CourseResource::collection($category->courses->where('is_visible' , '=' , true)));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function destroy($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $course->delete();
            return $this->success(CourseResource::make($course) , 'course deleted successfully');
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function cancelInfolement($inrolmentID){
        try {
            $inrolment = AccountInrolment::where('id' ,$inrolmentID )->first();
            if (!$inrolment){
                return $this->error(trans('messages.inrolment_not_found') , 404);
            }
            $inrolment->delete();
            return $this->success(null , trans('messages.inrolment_canceled'));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function makeCourseVisible($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $course->update([
                'is_visible' => true
            ]);
            return $this->success(CourseResource::make($course));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function makeCourseInvisible($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $course->update([
                'is_visible' => false
            ]);
            return $this->success(CourseResource::make($course));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function switchVisibility($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $course->update([
                'is_visible' => !$course->is_visible
            ]);
            return $this->success(CourseResource::make($course));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }


    public function switchOpenStatus($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            $course->update([
                'is_open' => !$course->is_open
            ]);
            return $this->success(CourseResource::make($course));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function manualInrolStudentInCourse($userID , $courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            $user = HelperFunction::getUserById($userID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            if (!boolval($course->is_visible)){
                return $this->error('this course invisible , you can\'t add any body for it' , 403);
            }
            if (!$user){
                return $this->error('user dose\'nt found in our system' , 404);
            }
            $preInrolled = AccountInrolment::where('user_id' , $userID)->where('course_id' , $courseID)->first();
            if ($preInrolled){
                return $this->error( $user->full_name . ' already sign in ' . $course->name , 422);
            }
            AccountInrolment::create([
                'user_id' => $user->id,
                'course_id' => $course->id
            ]);
            return $this->success(null ,$user->full_name . ' sign in ' . $course->name . ' successfully' );
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function store(StoreCourseRequest $request){
        try {
            DB::beginTransaction();
            $course = Course::create($request->only(['name', 'image', 'is_visible', 'is_open' , 'telegram_channel_link']));
            if ($request->categories) {
                foreach ($request->categories as $category) {
                    CourseCategory::create([
                        'course_id' => $course->id,
                        'category_id' => $category
                    ]);
                }
            } else {
                return $this->error('you must provide one category at least', 422);
            }
            if ($request->teachers) {
                foreach ($request->teachers as $teacher) {
                    CourseTeacher::create([
                        'course_id' => $course->id,
                        'teacher_id' => $teacher
                    ]);
                }
            } else {
                return $this->error('you must provide one teacher teach this course at least', 422);
            }
            if ($request->values) {
                foreach ($request->values as $value) {
                    CourseValue::create([
                        'course_id' => $course->id,
                        'value' => $value
                    ]);
                }
            }
            DB::commit();
            $notification = new NotificationController();
            $notification->addNewCourseNotification($course);
            return $this->success(CourseResource::make($course) , 'course ' . $course->name . ' created successfully');
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }

    public function showCourseWithInfo($courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error(trans('messages.course_not_found') , 404);
            }
            if (Auth::user()->type === UserType::ADMIN){
                return $this->success(ShowCourseInformationForAdmin::make($course));
            }
            return $this->success(CourseInformationResource::make($course));
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function update(UpdateCourseRequest $request , $courseID){
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error('course dose\'nt found in our system' , 404);
            }
            DB::beginTransaction();
            $course->update($request->only(['name' , 'image' , 'is_open' , 'is_visible' , 'telegram_channel_link']));
            $course->categories()->sync($request->categories);
            $course->teachers()->sync($request->teachers);
            DB::commit();
            return $this->success(CourseResource::make($course) , trans('messages.success_message'));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }

    public function inrollInCourse (SignInCourseRequest $request , $courseID) {
        $request->validated($request->only('code'));
        try {
            $course = HelperFunction::getCourseByID($courseID);
            if (!$course){
                return $this->error(trans('messages.course_not_found') , 404);
            }
            if (!boolval($course->is_visible)){
                return $this->error(trans('messages.course_invisible_sign_in') , 403);
            }
            $existingInrole = AccountInrolment::where('user_id' , $request->user()->id)
                ->where('course_id' , $courseID)->first();
            if ($existingInrole){
                return $this->error(trans('messages.already_sign_in_course') , 403);
            }
            $code = HelperFunction::getActivationCodeByCode(strval($request->activation_code));
            if (!$code){
                return $this->error(trans('messages.activation_code_not_found'), 403);
            }
            if (intval($code->times_of_usage) === 0){
                return $this->error(trans('messages.activation_code_expired') , 403);
            }
            if ($code->type === CodeType::SHARED_SELECTED){
                $courseAlreadyActivatedByCode = CourseCanActivated::where('activation_code_id' , $code->id)
                    ->where('course_id' , $course->id)->first();
                if ($courseAlreadyActivatedByCode){
                    return $this->error(trans('messages.shared_activation_code_already_used_for_this_course')  , 403);
                }
                DB::beginTransaction();
                $code->update([
                   'times_of_usage' =>  intval($code->times_of_usage) - 1
                ]);

                AccountInrolment::create([
                   'user_id' => Auth::user()->id,
                    'activation_code_id' => $code->id,
                    'course_id' => $course->id
                ]);

                CourseCanActivated::create([
                    'course_id' => $course->id,
                    'activation_code_id' => $code->id,
                    'is_used' => true
                ]);
                DB::commit();
                return $this->success(null , trans('messages.sign_in_course_successfully' , ['course' => $course->name]));
            }
            $coursesCanBeActivatedByThisCode =
                collect($code->courseCanActivated)
                    ->filter(fn($code) => intval($code->course_id) === intval($course->id));

            if (count($coursesCanBeActivatedByThisCode) === 0){
                return $this->error(trans('messages.wrong_match_course_with_code'), 403);
            }
            $courseToActive = $coursesCanBeActivatedByThisCode->first();
            if ($courseToActive->is_used){
                return $this->error(trans('messages.already_sign_in_course'), 422);
            }
            DB::beginTransaction();
            AccountInrolment::create([
                'course_id' => $course->id,
                'user_id' => Auth::user()->id,
                'activation_code_id' => $code->id
            ]);
            $code->update([
                'times_of_usage' => intval($code->times_of_usage) - 1
            ]);
            CourseCanActivated::where('id' , $courseToActive->id)->update(['is_used' => true]);
            DB::commit();
            return $this->success(null , trans('messages.sign_in_course_successfully' , ['course' => $course->name]));
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }
}
