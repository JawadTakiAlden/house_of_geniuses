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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => asset($this->image),
            'clarification_image' => asset($this->clarification_image),
            'clarification_text' => $this->clarification_text,

            'choices' => ChoiceResource::collection($this->choices)
        ];
    }
}
