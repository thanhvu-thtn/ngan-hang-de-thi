<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // Lấy model questionType đang được truyền vào qua Route
        $questionType = $this->route('question_type'); 

        return [
            'name' => 'required|string|max:255',
            // Dùng Rule::unique để báo cho Laravel bỏ qua ID của bản ghi hiện tại khi check trùng
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('question_types', 'code')->ignore($questionType)
            ],
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
        ];
    }
}