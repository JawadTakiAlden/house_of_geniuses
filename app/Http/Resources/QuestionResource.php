<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $baseData = [
            'id' => $this->id,
            'title' => $this->title,
            'image' => asset($this->image),
            'clarification_image' => asset($this->clarification_image),
            'clarification_text' => $this->clarification_text,
            'choices' => ChoiceResource::collection($this->choices),
        ];

        // if return this reosurce from quiz resource its should be merged with pivot table
        $pivot = $this->pivot;
        if ($pivot){
            $baseData = array_merge($baseData ,['questionQuiz' => [
                'id' => $pivot->id,
                'is_visible' => $pivot->is_visible,
            ]
            ]);
        }

        return $baseData;
    }
}
