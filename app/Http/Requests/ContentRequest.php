<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Phân quyền sẽ xử lý ở Controller
    }

    public function rules(): array
    {
        return [
            'topic_id' => 'required|exists:topics,id',
            'name'     => 'required|string|max:255',
            'order'    => 'nullable|integer',
            'periods'  => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'topic_id.required' => 'Vui lòng chọn chuyên đề.',
            'name.required'     => 'Vui lòng nhập tên nội dung.',
            'periods.required'  => 'Vui lòng nhập số tiết học.',
        ];
    }
}