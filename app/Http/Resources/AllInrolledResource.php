<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllInrolledResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->user->full_name,
            'course_title' => $this->course->name,
            'enrole_date' => $this->created_at->diffForHumans(),
            'code' => $this->activationCode
        ];
    }
}
