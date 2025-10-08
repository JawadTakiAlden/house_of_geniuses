<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequestV2 extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "string|required|max:1000",
            "choices" => "array|required",
            "choices.*.title" => "string|required|max:1000",
            "choices.*.is_true" => "required",
            "choices.*.is_visible" => "required",
        ];
    }
}
