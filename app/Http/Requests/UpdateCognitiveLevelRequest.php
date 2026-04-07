<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCognitiveLevelRequest extends FormRequest // <-- Chỗ này đã được đổi tên
{
    public function authorize() { return true; }

    public function rules()
    {
        // 1. Lấy param từ URL (có thể nó là Object, cũng có thể nó là chuỗi ID)
        $cognitiveLevel = $this->route('cognitive_level');
        
        // 2. Trích xuất ID an toàn: Nếu là Object thì trỏ tới id, nếu là chuỗi/số thì lấy luôn chính nó
        $id = is_object($cognitiveLevel) ? $cognitiveLevel->id : $cognitiveLevel;   
        return [
            'name' => 'required|string|max:255',
            'level_weight' => 'required|integer|min:1',
            'tag_name' => 'required|string|max:50|unique:cognitive_levels,tag_name,' . $id,
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập tên mức độ nhận thức.',
            'level_weight.required' => 'Vui lòng nhập trọng số (level weight).',
            'level_weight.integer' => 'Trọng số phải là số nguyên.',
            'level_weight.min' => 'Trọng số phải lớn hơn hoặc bằng 1.',
            'tag_name.required' => 'Vui lòng nhập tên thẻ (tag name).',
            'tag_name.unique' => 'Tên thẻ (tag name) đã tồn tại.',
        ];
    }
}