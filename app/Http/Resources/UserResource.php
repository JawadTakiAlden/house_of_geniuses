<?php

namespace App\Http\Resources;

use App\Http\HelperFunction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'image' => $this->image ? HelperFunction::getImage($this->image) : null,
            'is_blocked' => boolval($this->is_blocked),
            'type' => $this->type,
        ];
    }
}
