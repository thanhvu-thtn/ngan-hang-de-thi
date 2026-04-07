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
            'tag_name' => 'required|string|max:50|unique:cognitive_levels,tag_name',
            'level_weight' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên mức độ nhận thức.',
            'level_weight.required' => 'Vui lòng nhập trọng số (level weight).',
            'tag_name.required' => 'Vui lòng nhập tên thẻ (tag name).',
            'tag_name.unique' => 'Tên thẻ (tag name) đã tồn tại.',
            'level_weight.integer' => 'Trọng số phải là số nguyên.',
            'level_weight.min' => 'Trọng số phải lớn hơn hoặc bằng 1.',
        ];
    }
}