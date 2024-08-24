<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReOrderLesionsRequest extends FormRequest
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
            'lesions' => 'array',
            'lesions.*.id' => 'required|numeric|exists:lesions,id',
            'lesions.*.sort' => 'required|numeric',
        ];
    }
}
