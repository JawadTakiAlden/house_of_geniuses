<?php

namespace App\Http\Controllers;

use App\Http\HelperFunction;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\StoreQuestionRequestV2;
use App\Http\Requests\UpdateQuestionRequest;
use App\Http\Requests\UpdateQuestionRequestV2;
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
    public function getAll()
    {
        try {
            $questions = Question::all();
            return $this->success(QuestionResource::collection($questions));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function store(StoreQuestionRequest $request)
    {
        try {
            $question = Question::create($request->only(['title', 'image', 'clarification_text', 'clarification_image']));
            return $this->success(QuestionResource::make($question), __('messages.question_controller.create'));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function store_v2(StoreQuestionRequestV2 $request)
    {
        try {
            $question = Question::create($request->only(['title']));
            if ($request->choices) {
                foreach ($request->choices as $choice) {
                    Choice::create([
                        "question_id" => $question->id,
                        "title" => $choice["title"],
                        "is_true" => $choice["is_true"],
                        "is_visible" => $choice["is_visible"]
                    ]);
                }
            }
            return $this->success(QuestionResource::make($question), __('messages.question_controller.create'));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse($th);
        }
    }

    public function update_v2(UpdateQuestionRequestV2 $request, $question_id)
    {
        try {

            $question = Question::where("id", $question_id)->first();
            if (!$question) {
                return HelperFunction::notFoundResponce();
            }

            $question->update($request->only(["title"]));


            if ($request->new_choices) {
                foreach ($request->new_choices as $choice) {
                    Choice::create([
                        "question_id" => $question->id,
                        "title" => $choice["title"],
                        "is_true" => $choice["is_true"],
                        "is_visible" => $choice["is_visible"]
                    ]);
                }
            }

            if ($request->delete_choices) {
                foreach ($request->delete_choices as $choice) {
                    Choice::where("id", $choice)->delete();
                }
            }

            return $this->success(QuestionResource::make($question), __('messages.question_controller.create'));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse($th);
        }
    }

    public function update(UpdateQuestionRequest $request, $questionID)
    {
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$questionID) {
                return HelperFunction::notFoundResponce();
            }
            $question->update($request->only(['title', 'image', 'clarification_text', 'clarification_image']));
            return $this->success(QuestionResource::make($question), __('messages.question_controller.update'));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function show($questionID)
    {
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$question) {
                return HelperFunction::notFoundResponce();
            }
            return $this->success(QuestionResource::make($question));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse();
        }
    }

    public function destroy($questionID)
    {
        try {
            $question = HelperFunction::getQuestionByID($questionID);
            if (!$question) {
                return HelperFunction::notFoundResponce();
            }
            $question->delete();
            return $this->success(QuestionResource::make($question), __('messages.question_controller.delete'));
        } catch (\Throwable $th) {
            return HelperFunction::ServerErrorResponse();
        }
    }
}
