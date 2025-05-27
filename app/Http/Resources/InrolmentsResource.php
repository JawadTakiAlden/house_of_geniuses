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
            'id' => intval($this->id),
            'name' => $this->name,
            'image' => $this->image ? HelperFunction::getImage($this->image) : null,
            'telegram_channel_link' => $this->telegram_channel_link,
            'created_at' => $this->pivot->created_at->diffForHumans(),
            "activation_code" => $this->pivot->activationCode
        ];
    }
}
