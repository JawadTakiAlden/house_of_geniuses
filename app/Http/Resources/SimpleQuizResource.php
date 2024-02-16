<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SimpleQuizResource extends JsonResource
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
            'number_of_questions' => $this->numberOfQuestions,
            'number_of_visible_question' => $this->numberOfVisibleQuestion,
            'number_of_invisible_question' => $this->numberOfInVisibleQuestion
        ];
    }
}
