<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCognitiveLevelRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'level_weight' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên mức độ nhận thức.',
            'level_weight.required' => 'Vui lòng nhập trọng số (level weight).',
            'level_weight.integer' => 'Trọng số phải là số nguyên.',
            'level_weight.min' => 'Trọng số phải lớn hơn hoặc bằng 1.',
        ];
    }
}