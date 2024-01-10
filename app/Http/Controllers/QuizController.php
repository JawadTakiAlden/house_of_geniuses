<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\StoreNewQuestionInQuizRequest;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\HttpResponse\HTTPResponse;
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
            return $this->success(QuizResource::collection($quizzes));
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
            return $this->success(QuizResource::make($quiz) , trans('messages.delete_update'));
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
            return $this->success(QuizResource::make($quiz) , trans('messages.delete_quiz'));
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
            return $this->success(QuizResource::make($quiz) , trans('messages.question_add_to_quiz'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function deleteQuestionFromQuiz($quizQuestionID){
        try {
            $questionQuiz = QuestionQuiz::where('id' , $quizQuestionID)->first();
            if (!$questionQuiz){
                return $this->error(trans('messages.question_quiz_not_found') , 404);
            }
            $questionQuiz->delete();
            return $this->success(QuizResource::make($questionQuiz->quiz) , trans('messages.delete_question_quiz'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }


    public function switchVisibility($quizQuestionID){
        try {
            $questionQuiz = QuestionQuiz::where('id' , $quizQuestionID)->first();
            if (!$questionQuiz){
                return $this->error(trans('messages.question_quiz_not_found') , 404);
            }
            $questionQuiz->update([
                'is_visible' => !$questionQuiz->is_visible
            ]);
            return $this->success(QuizResource::make($questionQuiz->quiz) , trans('messages.switch_visibility_question_quiz'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }
}
