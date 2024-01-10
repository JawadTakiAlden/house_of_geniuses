<?php

namespace App\Http\Controllers;

use App\HttpResponse\HTTPResponse;
use App\Models\AccountInrolment;
use App\Models\ActivationCode;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseCanActivated;
use App\Models\User;
use App\Types\CodeType;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
    public function statistics(){
        try {
            $students_number = User::where('type' , UserType::STUDENT)->count();
            $codes_number = ActivationCode::count();
            $active_code_number = ActivationCode::whereHas('courseCanActivated' , fn($query) =>
            $query->where('is_used' , true)
            )->count();
            $courses_number = Course::count();
            $blocked_accounts_number = User::where('is_blocked' , true)->count();
            $categories_number = Category::count();

            $categories = Category::with('courses')->get();
            $courses = Course::with('accountInrolments')->get();

            $number_of_course_in_each_category = $categories->map(function($category) {
                return [
                    'category_name' => $category->name,
                    'number_of_courses' => $category->courses->count()
                ];
            });
            $number_of_user_in_each_course = $courses->map(fn($course) =>
                         [
                            'course_name' => $course->name,
                            'user_count_by_shared' => AccountInrolment::where('course_id' , $course->id)
                                ->whereHas('activationCode' , fn($query) =>
                                $query->where('type' ,CodeType::SHARED)
                                )->count(),
                            'user_count_by_shared_selected' => AccountInrolment::where('course_id' , $course->id)
                                ->whereHas('activationCode' , fn($query) =>
                                $query->where('type' ,CodeType::SHARED_SELECTED)
                                )->count(),
                            'user_count_by_single' => AccountInrolment::where('course_id' , $course->id)
                                ->whereHas('activationCode' , fn($query) =>
                                $query->where('type' ,CodeType::SINGLE)
                                )->count()
                        ]
                );
            return $this->success([
                'students_number' => $students_number,
                'codes_number'=> $codes_number,
                'active_code_number'=> $active_code_number,
                'courses_number'=> $courses_number,
                'blocked_accounts_number'=> $blocked_accounts_number,
                'categories_number'=> $categories_number,
                'number_of_course_in_each_category' => $number_of_course_in_each_category,
                'number_of_user_in_each_course' => $number_of_user_in_each_course
            ]);
        }catch (\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function reset(){
        try {
        DB::beginTransaction();
        AccountInrolment::truncate();
        ActivationCode::truncate();
        CourseCanActivated::truncate();
        DB::commit();
        return $this->success(null , 'statistics reset successfully and all activation codes deleted');
        }catch (\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }
}
