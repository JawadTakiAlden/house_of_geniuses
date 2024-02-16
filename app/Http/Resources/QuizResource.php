<?php

namespace App\Http\Resources;

use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'questions' => $request->user()->type === UserType::ADMIN ? QuestionResource::collection($this->forAdminQuestions) : QuestionResource::collection($this->forUserQuestions)
        ];
    }
}
