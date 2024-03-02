<?php

namespace App\Http\Resources;

use App\Models\AccountInrolment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckActivationCodeResource extends JsonResource
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
            'code' => $this->code,
            'type' => $this->type,
            'is_expired' => $this->isExpired,
            'courses' => $this->courseCanActivated->map(function($obj){
                $base = [
                    'course_name' => $obj->course->name,
                    'is_used' => boolval($obj->is_used),
                ];
                if (boolval($obj->is_used)){
                    $base = array_merge($base , [
                        'activator' => AccountInrolment::where('course_id' , $obj['course_id'])
                            ->where('activation_code_id' , $this->id)
                            ->first()->user->full_name
                    ]);
                }

                return $base;
            })
        ];
    }
}
