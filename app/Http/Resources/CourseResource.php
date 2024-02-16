<?php

namespace App\Http\Resources;

use App\Models\AccountInrolment;
use App\Types\UserType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
{
    protected static $accountInrollemnt;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!isset(self::$accountInrollemnt)) {
            $authenticatedUser = $request->user();
            self::$accountInrollemnt = $authenticatedUser->accountInrolments()->pluck('course_id');
        }
        $is_paid = self::$accountInrollemnt->contains($this->id);
//        $authenticatedUser = $request->user();
//        $accountInrollemnt = $authenticatedUser->accountInrolments()->pluck('course_id');
//        $is_paid = $accountInrollemnt->contains($this->id);
//        if ($authenticatedUser){
//            $inrole = AccountInrolment::where('user_id' , $authenticatedUser->id)
//                ->where('course_id' , $this->id)->exists();
//
//            if ($inrole){
//                $is_paid = true;
//            }
//        }
        $array = [
            'id' => intval($this->id),
            'name' => $this->name,
            'image' => asset($this->image),
            'telegram_channel_link' => $this->telegram_channel_link,
            'is_open' => boolval($this->is_open),
            'is_visible' => boolval($this->is_visible),
            'is_paid' => $is_paid,
        ];
        if (strval(Auth::user()->type) === UserType::STUDENT || strval(Auth::user()->type) === UserType::TEACHER){
            $array = array_merge($array , ['teachers' => $this->teachers->map(fn($teacher) => $teacher->full_name)]);
        }

        return  $array;
    }
}
