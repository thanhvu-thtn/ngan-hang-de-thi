<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionSetupRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'tag_name'      => 'required|string|unique:questions,tag_name',
            'type'          => 'required|exists:question_types,id',
            'level'         => 'required|exists:cognitive_levels,id',
            'objective_ids' => 'required|array',
            'objective_ids.*' => 'exists:objectives,id',
        ];
    }

    /**
     * Tùy chỉnh thông báo lỗi (Messages)
     */
    public function messages(): array
    {
        return [
            'tag_name.unique'        => 'Mã / Từ khóa này đã tồn tại, vui lòng nhập mã khác.',
            'objective_ids.required' => 'Bạn phải chọn ít nhất 1 mục tiêu đánh giá.',
            'name.required'          => 'Vui lòng nhập tên câu hỏi.',
            'type.required'          => 'Vui lòng chọn loại câu hỏi.',
            'level.required'         => 'Vui lòng chọn mức độ nhận thức.',
        ];
    }
}
