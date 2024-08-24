<?php

namespace App\Http\Resources;

use App\Models\ActivationCode;
use App\Types\CodeType;
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
        $count_by_single = intval($this->numberOfEnrolmentBySingleCode()['count_by_single']);
        $count_by_shared = intval($this->numberOfEnrolmentBySingleCode()['count_by_shared']);
        $count_by_shared_selected = intval($this->numberOfEnrolmentBySingleCode()['count_by_shared_selected']);
        $count_by_gift = intval($this->numberOfEnrolmentBySingleCode()['count_by_gift']);
        $manualEnrolment = intval($this->numberOfEnrolmentBySingleCode()['manual_enrolment']);

        $total_single_code_generated = intval($this->getTotalCodeGeneratedByType(CodeType::SINGLE));
        $total_shared_code_generated = intval($this->getTotalCodeGeneratedByType(CodeType::SHARED));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total' => $count_by_single
                +$count_by_shared
                +$count_by_shared_selected
                +$manualEnrolment
                +$count_by_gift,
            'total_single_code_generated' => $total_single_code_generated,
            'total_shared_code_generated' => $total_shared_code_generated,
            'number_single_code_enrolment' => $count_by_single,
            'number_shared_code_enrolment' => $count_by_shared,
            'number_shared_selected_code_enrolment' => $count_by_shared_selected,
            'number_gift_code_enrolment' => $count_by_gift,
            'manual_enrolment' => $manualEnrolment
        ];
    }
}
