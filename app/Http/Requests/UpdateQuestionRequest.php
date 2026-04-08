<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // CHỐT CHẶN 2 (Bảo mật sâu): Kiểm tra lại lần nữa khi gửi form
        $question = $this->route('question'); // Lấy object question từ route

        if ($question->status == 1 && ! $this->user()->can('tham-dinh-cau-hoi')) {
            return false; // Trả về 403 Forbidden
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $questionId = $this->route('question')->id;

        // 1. Luật chung
        $rules = [
            'name' => 'required|string|max:255',
            // Bỏ qua ID của câu hỏi hiện tại khi check unique
            'tag_name' => 'required|string|max:255|unique:questions,tag_name,'.$questionId,
            'objective_ids' => 'required|array|min:1',
            'cognitive_level_id' => 'required|exists:cognitive_levels,id',
            'type_code' => 'required|exists:question_types,code',
        ];

        // 2. Luật cho người CÓ QUYỀN THẨM ĐỊNH
        if ($this->user()->can('tham-dinh-cau-hoi')) {
            $rules['status'] = 'required|in:0,1,2';
            $rules['difficulty_index'] = 'nullable|numeric|min:0|max:100'; // Tùy dải điểm độ khó của bạn
        }

        // 3. Luật riêng theo loại câu hỏi (ES, MC...)
        $typeCode = $this->input('type_code');
        if ($typeCode === 'ES') {
            $rules['stem'] = 'required|string';
            $rules['explanation'] = 'nullable|string';
        }

        return $rules;
    }
}
