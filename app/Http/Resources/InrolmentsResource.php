<?php

namespace App\Http\Resources;

use App\Http\HelperFunction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InrolmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => intval($this->course->id),
            'name' => $this->course->name,
            'image' => $this->course->image ? HelperFunction::getImage($this->course->image) : null,
            'telegram_channel_link' => $this->course->telegram_channel_link,
            'created_at' => $this->created_at->diffForHumans(),
            "activation_code" => [
                "type" => $this->activationCode
            ]
        ];
    }
}
