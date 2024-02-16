<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\AddQuizToChapterRequest;
use App\Http\Requests\DeleteQuestionFromQuiz;
use App\Http\Requests\QuestionFromQuizRequest;
use App\Http\Requests\StoreNewQuestionInQuizRequest;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\QuizResource;
use App\Http\Resources\SimpleQuizResource;
use App\HttpResponse\HTTPResponse;
use App\Models\ChapterQuiz;
use App\Models\Question;
use App\Models\QuestionQuiz;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
    public function getAll(){
        try {
            $quizzes = Quiz::orderBy('created_at')->get();
            return $this->success(SimpleQuizResource::collection($quizzes));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }
    public function store(StoreQuizRequest $request){
        try {
            DB::beginTransaction();
            $quiz = Quiz::create($request->only(['title' , 'description']));
            foreach ($request->questions as $question){
                QuestionQuiz::create([
                    'question_id' => $question,
                    'quiz_id' => $quiz->id,
                    'is_visible' => $request->is_visible
                ]);
            }
            DB::commit();
            return $this->success(QuizResource::make($quiz) , trans('messages.create_quiz'));
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }

    public function show($quizID){
        try {
            $quiz = HelperFunction::getQuizByID($quizID);
            if (!$quiz){
                return $this->error(trans('messages.quiz_not_found') , 404);
            }
            return $this->success(QuizResource::make($quiz));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function update(UpdateQuizRequest $request , $quizID){
        try {
            $quiz = HelperFunction::getQuizByID($quizID);
            if (!$quiz){
                return $this->error(trans('messages.quiz_not_found') , 404);
            }
            $quiz->update($request->only(['title' , 'description']));
            return $this->success(null, trans('messages.delete_update'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function destroy($quizID){
        try {
            $quiz = HelperFunction::getQuizByID($quizID);
            if (!$quiz){
                return $this->error(trans('messages.quiz_not_found') , 404);
            }
            $quiz->delete();
            return $this->success(null, trans('messages.delete_quiz'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function addQuestionToQuiz(StoreNewQuestionInQuizRequest $request , $quizID){
        try {
            $quiz = HelperFunction::getQuizByID($quizID);
            if (!$quiz){
                return $this->error(trans('messages.quiz_not_found') , 404);
            }
            $question = Question::where('id' , $request->question_id)->exists();
            if (!$question){
                return $this->error(trans('messages.question_not_found') ,404);
            }
            $isAddBefore = QuestionQuiz::where('quiz_id' , $quizID)->where('question_id' , $request->question_id)->exsits();
            if ($isAddBefore){
                return $this->error(trans('messages.question_pre_add_to_quiz') , 422);
            }
            QuestionQuiz::create([
               'quiz_id' => $quizID,
               'question_id' => $request->question_id,
               'is_visible' => $request->is_visible
            ]);
            return $this->success(null, trans('messages.question_add_to_quiz'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function deleteQuestionFromQuiz(QuestionFromQuizRequest $request){
        try {
            DB::beginTransaction();
            if ($request->questionQuizzes){
                foreach ($request->questionQuizzes as $questionQuizz){
                    QuestionQuiz::where('id' , $questionQuizz)->delete();
                }
            }
            DB::commit();
            return $this->success(null , trans('messages.delete_question_quiz'));
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }


//    public function switchVisibility($quizQuestionID){
//        try {
//            $questionQuiz = QuestionQuiz::where('id' , $quizQuestionID)->first();
//            if (!$questionQuiz){
//                return $this->error(trans('messages.question_quiz_not_found') , 404);
//            }
//            $questionQuiz->update([
//                'is_visible' => !$questionQuiz->is_visible
//            ]);
//            return $this->success(null, trans('messages.switch_visibility_question_quiz'));
//        }catch(\Throwable $th){
//            return $this->catchError($th);
//        }
//    }


    public function hideVisibility(QuestionFromQuizRequest $request){
        try {
            DB::beginTransaction();
            if ($request->questionQuizzes){
                foreach ($request->questionQuizzes as $questionQuizz){
                    QuestionQuiz::where('id' , $questionQuizz)->update([
                        'is_visible' => false
                    ]);
                }
            }
            DB::commit();
            return $this->success(null , trans('messages.delete_question_quiz'));
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }

    public function showVisibility(QuestionFromQuizRequest $request){
        try {
            DB::beginTransaction();
            if ($request->questionQuizzes){
                foreach ($request->questionQuizzes as $questionQuizz){
                    QuestionQuiz::where('id' , $questionQuizz)->update([
                        'is_visible' => true
                    ]);
                }
            }
            DB::commit();
            return $this->success(null , trans('messages.delete_question_quiz'));
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }

    public function addQuizToChapter(AddQuizToChapterRequest $request){
        try {
            $quizID = intval($request->quiz_id);
            $chapterID = intval($request->chapter_id);
            $quiz = Quiz::where('id' , $quizID)->first();
            $chapter  = HelperFunction::getChapterByID($chapterID);
            if (!$quiz){
                return $this->error(trans('messages.quiz_not_found') , 404);
            }
            if (!$chapter){
                return $this->error(trans('messages.chapter_not_found') , 404);
            }
            $isAddedBefore = ChapterQuiz::where('quiz_id' , $quizID)->where('chapter_id' , $chapterID)->exists();
            if (!$isAddedBefore){
                return $this->error(trans('messages.quiz_added_before_to_chapter') , 422);
            }
            ChapterQuiz::create([
               'chapter_id' => $chapterID,
               'quiz_id' => $quizID,
               'is_visible' => $request->is_visible
            ]);
            return $this->success(null, trans('messages.quiz_added_to_chapter_successfully'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }
}
