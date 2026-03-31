<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionLayoutRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:question_layouts,code',
            'ratio' => 'required|numeric|min:0.1|max:1.0|multiple_of:0.1',
        ];
    }

    public function messages(): array
    {
        return [
            'ratio.multiple_of' => 'Tỉ lệ phải là bội số của 0.1 (VD: 0.1, 0.2, 0.5...). Không chấp nhận các số lẻ như 0.25.',
            'ratio.min' => 'Tỉ lệ nhỏ nhất là 0.1',
            'ratio.max' => 'Tỉ lệ lớn nhất là 1.0',
        ];
    }
}
