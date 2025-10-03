<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewSinginCourseRequest extends FormRequest
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
            'type' => 'required',
            'course_ids' => "array|required",
            "course_ids.*" => "numeric|required",
            "user_id" => "numeric|required|exists:users,id"
        ];
    }
}
