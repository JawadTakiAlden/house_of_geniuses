<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentWithTypeOfCodeResource extends JsonResource
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
            'name' => $this->name,
            'number_single_code_enrolment' => $this->numberOfEnrolmentBySingleCode(),
//            'number_shared_code_enrolment' => $this->numberOfEnrolmentBySharedCode(),
//            'number_shared_selected_code_enrolment' => $this->numberOfEnrolmentBySharedSelectedCode(),
//            'number_gift_code_enrolment' => $this->numberOfEnrolmentByGiftCode(),
        ];
    }
}
