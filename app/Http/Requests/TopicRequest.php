<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopicRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi request này không.
     * (Vì mình đã chặn bằng 403 trong Controller rồi nên ở đây cứ return true)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Chứa toàn bộ rules để validate.
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'topic_type_id' => 'required|exists:topic_types,id',
            'subject_id' => 'required|exists:subjects,id',
            'grade' => 'required|integer',
            'order' => 'nullable|integer',
            'total_periods' => 'required|integer|min:0',
        ];

        // Lấy user hiện tại đang đăng nhập bằng $this->user()
        if ($this->user()->hasRole('Admin')) {
            $rules['subject_id'] = 'required|exists:subjects,id';
        }

        return $rules;
    }

    /**
     * (Tùy chọn) Bạn có thể Việt hóa câu thông báo lỗi ở đây luôn nếu muốn
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên chuyên đề.',
            'topic_type_id.required' => 'Vui lòng chọn loại chuyên đề.',
            'total_periods.required' => 'Vui lòng nhập tổng số tiết.',
            'total_periods.integer' => 'Tổng số tiết phải là một số nguyên.',
            'total_periods.min' => 'Tổng số tiết phải lớn hơn hoặc bằng 0.',
            'subject_id.required' => 'Vui lòng chọn môn học.',
            'subject_id.exists' => 'Môn học không hợp lệ.',
            'grade.required' => 'Vui lòng nhập khối lớp.',
            'grade.integer' => 'Khối lớp phải là một số nguyên.',
            // ... các thông báo khác
        ];
    }
}