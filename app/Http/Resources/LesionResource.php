<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class LesionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => intval($this->id),
            'title' => $this->title,
            'link' => $this->type === 'pdf' ? Storage::get($this->link) : $this->link,
            'time' => intval($this->time),
            'is_open' => boolval($this->is_open),
            'is_visible' => boolval($this->is_visible),
            'type' => $this->type,
            'chapter_id' => intval($this->chapter_id),
        ];
    }

}
