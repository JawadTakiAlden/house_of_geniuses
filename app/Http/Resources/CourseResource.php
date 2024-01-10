<?php

namespace App\Http\Resources;

use App\Models\AccountInrolment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $is_paid = false;
        $authenticatedUser = $request->user();
        if ($authenticatedUser){
            $inrole = AccountInrolment::where('user_id' , $authenticatedUser->id)
                ->where('course_id' , $this->id)->exists();

            if ($inrole){
                $is_paid = true;
            }
        }

        return [
            'id' => intval($this->id),
            'name' => $this->name,
            'image' => asset($this->image),
            'telegram_channel_link' => $this->telegram_channel_link,
            'is_open' => boolval($this->is_open),
            'is_visible' => boolval($this->is_visible),
            'is_paid' => $is_paid,
        ];
    }
}
