<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Xác định xem user có quyền gọi request này không.
     * Để true vì hiện tại ta xử lý quyền ở Middleware hoặc Controller.
     */
    public function authorize()
    {
        return true; 
    }

    /**
     * Các luật kiểm tra dữ liệu đầu vào.
     */
    public function rules()
    {
        return [
            'tag_name'           => 'required|string|max:255|unique:questions,tag_name',
            'name'               => 'nullable|string|max:255',
            'question_type_id'   => 'required|integer|exists:question_types,id',
            'cognitive_level_id' => 'required|integer|exists:cognitive_levels,id',
            'objective_ids'      => 'required|array|min:1',
            'objective_ids.*'    => 'exists:objectives,id',
            'content'            => 'required|string',
            'solution'           => 'nullable|string',
        ];
    }

    /**
     * Tùy chỉnh câu thông báo lỗi bằng Tiếng Việt.
     */
    public function messages()
    {
        return [
            'tag_name.required'           => 'Mã định danh không được để trống.',
            'tag_name.unique'             => 'Mã định danh này đã tồn tại trong hệ thống.',
            'question_type_id.required'   => 'Vui lòng chọn loại câu hỏi.',
            'cognitive_level_id.required' => 'Vui lòng chọn mức độ nhận thức.',
            'objective_ids.required'      => 'Bạn phải chọn ít nhất một mục tiêu đánh giá.',
            'content.required'            => 'Nội dung câu hỏi không được để trống.',
        ];
    }
}
