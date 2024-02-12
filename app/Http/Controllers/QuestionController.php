<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Resources\QuestionResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Choice;
use App\Models\Question;
use App\Types\UpdateChoiceFlag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
    public function getAll(){
        try {
            $questions = Question::all();
            return $this->success(QuestionResource::collection($questions));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function store(StoreQuestionRequest $request){
        try {
            DB::beginTransaction();
            $question = Question::create($request->only(['title' ,'image' , 'clarification_text','clarification_image']));
            if ($request->choices){
                foreach ($request->choices as $choice){
                    $image = isset($choice['image']) ? $choice['image'] : null;
                    Choice::create([
                        'question_id' =>  $question->id,
                        'title' => $choice['title'],
                        'image' => $image,
                        'is_true' => $choice['is_true']
                    ]);
                }
            }
            DB::commit();
            return $this->success(QuestionResource::make($question) , trans('messages.create_question'));
        }catch(\Throwable $th){
            DB::rollBack();
            return $this->catchError($th);
        }
    }
    /* @commented code for update choices with update question process */
    public function update(UpdateQuestionRequest $request , $questionID){
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$questionID){
                return $this->error( trans('messages.question_not_found'), 404);
            }
            $question->update($request->only(['title' ,'image' , 'clarification_text','clarification_image']));
//            $this->updateQuestionChoices($question , $request->choices)
            return $this->success(QuestionResource::make($question) , trans('messages.update_question'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    /* @the function response to update choices in request depends in flag */
//    private function updateQuestionChoices($question , $choices){
//        foreach ($choices as $choice){
//            if (strval($choice->flag) === UpdateChoiceFlag::CREATE){
//                Choice::create([
//                   'title' => $choice->title,
//                   'image' => $choice->image,
//                   'question_id' => $question->id,
//                   'is_true' => $choice->is_true
//                ]);
//            }
//            if (strval($choice->flag) === UpdateChoiceFlag::DELETE){
//                Choice::where('id' , $choice->id)->delete();
//            }
//            if (strval($choice->flag) === UpdateChoiceFlag::UPDATE){
//                $choice = Choice::where('id' , $choice->id)->first();
//                $choice->update([
//                   'title' => $choice->title,
//                   'image' => $choice->image,
//                   'is_true' => $choice->is_true
//                ]);
//            }
//        }
//    }

    public function show($questionID){
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$question){
                return $this->error( trans('messages.question_not_found'), 404);
            }
            return $this->success(QuestionResource::make($question));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function destroy($questionID){
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$question){
                return $this->error( trans('messages.question_not_found'), 404);
            }
            $question->delete();
            return $this->success(QuestionResource::make($question) , trans('messages.question_not_found'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }
}
