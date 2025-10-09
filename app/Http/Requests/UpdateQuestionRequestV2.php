<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequestV2 extends FormRequest
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
            "title" => "required|string|max:1000",
            "new_choices" => "array",
            "new_choices.*.title" => "required|string",
            "new_choices.*.is_visible" => "required",
            "new_choices.*.is_true" => "require",
            "delete_choices" => "array",
            "delete_choices.*" => "numeric|required"
        ];
    }
}
