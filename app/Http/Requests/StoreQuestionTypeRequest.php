<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionTypeRequest extends FormRequest
{
    public function authorize()
    {
        // Trả về true để cho phép thực thi request này (vì phân quyền đã làm ở route)
        return true; 
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:question_types,code',
            'expected_choices_count' => 'required|integer|min:1|max:20',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên loại câu hỏi.',
            'code.required' => 'Vui lòng nhập mã (code) loại câu hỏi.',
            'code.unique' => 'Mã loại câu hỏi này đã tồn tại trong hệ thống.',
            'expected_choices_count.required' => 'Vui lòng nhập số lượng lựa chọn chuẩn.',
            'expected_choices_count.integer' => 'Số lượng lựa chọn chuẩn phải là một số nguyên.',
            'expected_choices_count.min' => 'Số lượng lựa chọn phải lớn hơn hoặc bằng 1.',
            'expected_choices_count.max' => 'Số lượng lựa chọn phải nhỏ hơn hoặc bằng 20.',
        ];
    }
}