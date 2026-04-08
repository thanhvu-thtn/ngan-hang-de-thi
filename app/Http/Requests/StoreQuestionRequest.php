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
        // 1. CÁC QUY TẮC DÙNG CHUNG (Cho mọi loại câu hỏi)
        $rules = [
            'name' => 'required|string|max:255',
            'tag_name' => 'required|string|max:255|unique:questions,tag_name',
            'objective_ids' => 'required|array|min:1',
            'objective_ids.*' => 'exists:objectives,id',
            'cognitive_level_id' => 'required|exists:cognitive_levels,id',
            'type_code' => 'required|exists:question_types,code',
        ];

        // 2. CÁC QUY TẮC RIÊNG (Tự động nạp thêm tùy theo type_code)
        $typeCode = $this->input('type_code');

        if ($typeCode === 'ES') {
            // Nếu là Tự luận
            $rules['stem'] = 'required|string';
            $rules['explanation'] = 'nullable|string';
        } elseif ($typeCode === 'MC') {
            // Nếu là Trắc nghiệm (Sau này bạn thêm rule vào đây)
            // $rules['stem'] = 'required|string';
            // $rules['choices'] = 'required|array|min:2';
        }
        // ... thêm các loại khác (TF, SA) sau này

        return $rules;
    }

    /**
     * Tùy chỉnh câu thông báo lỗi bằng Tiếng Việt.
     */
    public function messages()
    {
        return [
            // Thông báo chung
            'name.required' => 'Vui lòng nhập tóm tắt nội dung câu hỏi.',
            'tag_name.required' => 'Mã định danh không được để trống.',
            'tag_name.unique' => 'Mã định danh này đã tồn tại trong CSDL, vui lòng nhập mã khác.',
            'objective_ids.required' => 'Vui lòng chọn ít nhất một mục tiêu kiến thức từ cây chuyên đề.',
            'cognitive_level_id.required' => 'Vui lòng chọn mức độ nhận thức.',
            'type_code.required' => 'Hệ thống không nhận diện được loại câu hỏi.',
            
            // Thông báo riêng cho Tự luận (ES)
            'stem.required' => 'Nội dung câu hỏi (đề bài) không được để trống.',
        ];
    }
}
