<?php

namespace App\Http\Resources;

use App\Types\UserType;
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
        $data = [
            'id' => intval($this->id),
            'title' => $this->title,
            'link' => $this->type === 'pdf' ? asset('storage/'.$this->link) : 'https://api.houseofgeniuses.tech/api/v1/watch/'.$this->link,
            'time' => intval($this->time),
            'is_open' => boolval($this->is_open),
            'is_visible' => boolval($this->is_visible),
            'type' => $this->type,
            'chapter_id' => intval($this->chapter_id),
        ];
        if ($this->type === 'video' && strval($request->user()->type) === UserType::ADMIN){
            $data = array_merge($data , ['link_uri' => $this->link]);
        }
        return $data;
    }

}
