<?php

use App\Http\Controllers\ActivationCodeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\ChoiceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseValueController;
use App\Http\Controllers\LesionController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['language'])->group(function (){
    Route::prefix('/v1/auth/')->group(function (){
        Route::post('/register' , [AuthController::class , 'signup']);
        Route::post('/login' , [AuthController::class , 'login']);
        Route::post('/admin/login' , [AuthController::class , 'loginAdmin']);
    });
    /* @commented code for routes if the mobile can discover content without auth */
//    Route::prefix('/news')->group(function (){
//        Route::get('/visible' , [NewsController::class , 'visibleNews']);
//    });
//    Route::prefix('/categories')->group(function (){
//        Route::get('/visible' , [CategoryController::class , 'visibleCategories']);
//    });
//    Route::prefix('/courses')->group(function () {
//        Route::get('/visible/{category}', [CourseController::class, 'getVisibleCourses']);
//        Route::get('/show/{course}' , [CourseController::class , 'showCourseWithInfo']);
//    });
    Route::middleware(['auth:sanctum'])->group(function () {
//    Admin Routes
        Route::prefix('/v1')->group(function (){
            Route::middleware(['admin'])->group(function (){
                Route::prefix('/users')->group(function (){
                    Route::patch('/switchBlockAccount/{user}' , [UserController::class , 'switchBlockState']); //done
                    Route::get('/profileOf/{user}' , [UserController::class , 'getUserProfile']);
                    Route::post('/create' , [UserController::class , 'create']); // done
                    Route::get('/all' , [UserController::class , 'getAllUser']); // done
                    Route::get('/spicialAccounts' , [UserController::class , 'getSpicialAccounts']); //done
                    Route::get('/blocked' , [UserController::class , 'getAllBlockedUser']);
                    Route::get('/teachers' , [UserController::class , 'getTeacher']);
                    Route::get('/insideCourse/{course}' , [UserController::class , 'getAllUserThatSignInToThis']);
                    Route::get('/allCoursesOf/{user}' , [UserController::class , 'GetAllInrolnmentCourseForThis']); //done
                    Route::delete('/delete/{user}' , [UserController::class , 'destroy']); //done
                });
                Route::prefix('/news')->group(function (){
                    Route::get('/all' , [NewsController::class , 'index']);
                    Route::post('/create' , [NewsController::class , 'store']);
                    Route::delete('/delete/{news}' , [NewsController::class , 'destroy']);
                    Route::patch('/switchVisibility/{news}' , [NewsController::class , 'switchVisibility']);
                });
                Route::prefix('/categories')->group(function (){
                    Route::get('/all' , [CategoryController::class , 'gelAllCategories']);
                    Route::post('/create' , [CategoryController::class , 'store']);
                    Route::patch('/update/{category}' , [CategoryController::class , 'updateCategory']);
                    Route::patch('/switch-visibility/{category}' , [CategoryController::class , 'switchVisibility']);
                    Route::delete('/delete/{category}' , [CategoryController::class , 'destroy']);
                });
                Route::prefix('/courses')->group(function (){
                    Route::post('/create' , [CourseController::class , 'store']);
                    Route::post('/add-value/{course}' , [CourseValueController::class , 'store']);
                    Route::delete('/delete-value/{value}' , [CourseValueController::class , 'destroy']);
                    Route::patch('/update-value/{value}' , [CourseValueController::class , 'update']);
                    Route::get('/all' , [CourseController::class , 'getAllCourses']); //done
                    Route::get('/visible' , [CourseController::class , 'visibleCourses']); //used in api | done
                    Route::post('/update/{course}' , [CourseController::class , 'update']);
                    Route::patch('/switchOpenStatus/{course}' , [CourseController::class  , 'switchOpenStatus']); //done
                    Route::patch('/switchVisibility/{course}' , [CourseController::class , 'switchVisibility']); //done
                    Route::post('/addUser/{user}/toCourse/{course}' , [CourseController::class , 'manualInrolStudentInCourse']); //done
                    Route::get('/allInrolments' , [CourseController::class , 'getAllIneolments']);
                    Route::delete('/cancelInrolment/{inrollment}' , [CourseController::class , 'cancelInfolement']); //done
                    Route::delete('/delete/{course}' , [CourseController::class , 'destroy']);
                });
                Route::prefix('/chapters')->group(function (){
                    Route::get('/all/{course}' , [ChapterController::class , 'getAll']); //unused
                    Route::patch('/switchVisibility/{chapter}' , [ChapterController::class , 'switchVisibility']);
                    Route::post('/create' , [ChapterController::class , 'store']); //done
                    Route::patch('/update/{chapter}' , [ChapterController::class , 'update']);
                    Route::delete('/delete/{chapter}' , [ChapterController::class , 'destroy']); //done
                });
                Route::prefix('/lesions')->group(function (){
                    Route::get('/all/{chapter}' , [LesionController::class , 'getAll']);
                    Route::patch('/switchVisibility/{lesion}' , [LesionController::class , 'switchVisibility']);
                    Route::post('/create' , [LesionController::class , 'store']);
                    Route::patch('/update/{lesion}' , [LesionController::class , 'update']);
                    Route::delete('/delete/{lesion}' , [LesionController::class , 'delete']);
                });
                Route::prefix('/activationCodes')->group(function (){
                    Route::post('/generate' , [ActivationCodeController::class , 'store']);
                    Route::get('/unexpired' , [ActivationCodeController::class , 'getUnExpiredCodes']);
                });
                Route::prefix('/notifications')->group(function (){
                    Route::post('/push' , [NotificationController::class , 'sendNotificationForAllUser']);
                });
                Route::prefix('/statistics')->group(function (){
                    Route::get('/get' , [StatisticsController::class , 'statistics']); //done
                    Route::get('/basicStatistics' , [StatisticsController::class , 'basicStatistics']); //done
                    Route::get('/last-enrolled' , [StatisticsController::class , 'getLastEnrolled']); //done
                    Route::post('/reset' , [StatisticsController::class , 'reset']);
                });
                Route::prefix('/questions')->group(function () {
                    Route::get('/all' , [QuestionController::class , 'getAll']);
                    Route::get('/show/{question}' , [QuestionController::class , 'show']);
                    Route::post('/store' ,[QuestionController::class , 'store']);
                    Route::post('/update/{question}' , [QuestionController::class , 'update']);
                    Route::post('/newChoice/{question}' , [ChoiceController::class , 'store']);
                    Route::prefix('/choices')->group(function (){
                       Route::post('/update/{choice}' , [ChoiceController::class , 'updateChoice']);
                       Route::delete('/delete/{choice}' , [ChoiceController::class , 'destroy']);
                       Route::patch('/switch-to-true/{choice}' , [ChoiceController::class , 'makeChoiceTrue']);
                       Route::patch('/switch-visibility/{choice}' , [ChoiceController::class , 'switchVisibility']);
                    });
                    Route::post('/update/{question}' , [QuestionController::class , 'update']);
                    Route::delete('/delete/{question}' , [QuestionController::class , 'destroy']);
                });
                Route::prefix('/quizzes')->group(function (){
                    Route::get('/getAll' , [QuizController::class , 'getAll']);
                    Route::post('/create' , [QuizController::class , 'store']);
                    Route::patch('/update/{quiz}' , [QuizController::class , 'update']);
                    Route::delete('/delete/{quiz}' , [QuizController::class , 'destroy']);
                    Route::post('/addQuizToChapter' , [QuizController::class , 'addQuizToChapter']);
                    Route::prefix('/questions')->group(function (){
                       Route::post('/add/{quiz}' , [QuizController::class , 'addQuestionToQuiz']);
                       Route::delete('/delete/{quizQuestion}' , [QuizController::class , 'deleteQuestionFromQuiz']);
                       Route::patch('/switch-visibility/{quizQuestion}' , [QuizController::class , 'switchVisibility']);
                    });
                });
            });
//        Mobile App
            Route::middleware(['student_teacher_admin'])->group(function (){
                Route::middleware(['blocked_account'])->group(function (){
                    Route::prefix('/auth')->group(function (){
                        Route::post('/logout' , [AuthController::class , 'logout']);
                    });
                    Route::prefix('/users')->group(function (){
                        Route::post('/updateProfile/{user}' , [UserController::class , 'updateProfile']);
                        Route::get('/myProfile' , [UserController::class , 'profile']);
                        Route::get('/{user}/courses' , [UserController::class , 'inrollnmentCourseOfUser']);
                    });
                    Route::prefix('/news')->group(function (){
                        Route::get('/visible' , [NewsController::class , 'visibleNews']);
                    });
                    Route::prefix('/categories')->group(function (){
                        Route::get('/visible' , [CategoryController::class , 'visibleCategories']);
                    });
                    Route::prefix('/courses')->group(function (){
                        Route::get('/visible/{category}', [CourseController::class, 'getVisibleCourses']);
                        Route::get('/show/{course}' , [CourseController::class , 'showCourseWithInfo']);
                        Route::post('/signIn/{course}' , [CourseController::class , 'inrollInCourse']);
                    });
                });
            });
        });
    });
});
