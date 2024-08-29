<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterInformationForAdmin extends JsonResource
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
            'lesions' => LesionResource::collection($this->lesions()->orderBy('sort')->get()),
            'quizzes' => SimpleQuizResource::collection($this->quizzes->map(function($quiz) use ($chapter_id) {
                $quiz['chapter_id'] = $chapter_id;
                return $quiz;
            }
            )),
            'sort' => $this->sort
        ];
    }
}
