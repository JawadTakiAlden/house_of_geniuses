<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\StoreChoiceRequest;
use App\Http\Requests\UpdateChoiceRequest;
use App\Http\Resources\QuestionResource;
use App\HttpResponse\HTTPResponse;
use App\Models\Choice;
use Illuminate\Http\Request;

class ChoiceController extends Controller
{
    use HTTPResponse;
    private function catchError ($th) {
        return $this->error($th->getMessage() , 500);
    }
    public function store(StoreChoiceRequest $request , $questionID){
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$question){
                return $this->error(trans('messages.question_not_found') , 404);
            }
            if ($request->is_true){
                $question->choices()->where('is_true' , true)->update(['is_true' => false]);
            }
            Choice::create(array_merge($request->only(['title' , 'is_visible' , 'image' , 'is_true']) , ['question_id' => $questionID] ));
            return $this->success(QuestionResource::make($question) , trans('messages.create_choice'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function updateChoice(UpdateChoiceRequest $request , $choiceID){
        try {
            $choice = HelperFunction::getChoiceByID($choiceID);
            if (!$choice){
                return $this->error(trans('messages.choice_not_found') , 404);
            }
            $choice->update($request->only(['title','image']));
            return $this->success(QuestionResource::make($choice->question) , trans('messages.update_choice'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function destroy($choiceID){
        try {
            $choice = HelperFunction::getChoiceByID($choiceID);
            if (!$choice){
                return $this->error(trans('messages.choice_not_found') , 404);
            }
            $choice->delete();
            return $this->success(QuestionResource::make($choice->question) , trans('messages.delete_choice'));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function  makeChoiceTrue($choiceID){
        try {
            $choice = HelperFunction::getChoiceByID($choiceID);
            if (!$choice){
                return $this->error(trans('messages.choice_not_found') , 404);
            }
            $choice->question->choices()->where('is_true' , true)->update([
                'is_true' => false
            ]);
            $choice->update([
                'is_true' => true
            ]);
            return $this->success(QuestionResource::make($choice->question));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

    public function switchVisibility($choiceID){
        try {
            $choice = HelperFunction::getChoiceByID($choiceID);
            if (!$choice){
                return $this->error(trans('messages.choice_not_found') , 404);
            }
            $choice->update([
                'is_visible' => !$choice->is_visible
            ]);
            return $this->success(QuestionResource::make($choice->question));
        }catch(\Throwable $th){
            return $this->catchError($th);
        }
    }

}
