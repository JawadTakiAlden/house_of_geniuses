<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterInforamtionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $chapter_id = $this->id;
        return [
            'id' => intval($this->id),
            'name' => strval($this->name),
            'is_visible' => boolval($this->is_visible),
            'lesions' => LesionResource::collection($this->visibleLesions),
            'quizzes' => QuizResource::collection($this->visibleQuizzes->map(function($quiz) use ($chapter_id) {
                $quiz['chapter_id'] = $chapter_id;
                return $quiz;
            }
            )),
            'course_id' => $this->course_id
        ];
    }
}
