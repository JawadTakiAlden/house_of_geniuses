<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequestV2 extends FormRequest
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
            'type' => "required|string|in:pdf,video",
            "pdfFile" => "required_if:type,pdf|file|max:10240",
            "video_id" => "required_if:type,video|string",
            'is_visible' => 'required|boolean',
            'is_open' => 'required|boolean',
            'title' => 'nullable|string|max:255',
            'chapter_id' => "required|numeric|exists:chapters,id",
        ];
    }
}
