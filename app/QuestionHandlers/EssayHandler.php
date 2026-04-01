<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use App\Models\QuestionExplanation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EssayHandler implements QuestionHandlerInterface
{
    /**
     * Xác thực dữ liệu form gửi lên
     */
    public function validateData(Request $request): array
    {
        return $request->validate([
            'tag_name'           => 'nullable|string|max:255',
            'question_type_id'   => 'required|integer', // ID của loại câu Tự luận
            'cognitive_level_id' => 'required|integer', // Mức độ (Nhận biết, Thông hiểu...)
            'stem'               => 'required|string',  // Nội dung câu hỏi
            'explanation'        => 'required|string',  // Hướng dẫn chấm / Lời giải
            'difficulty_index'   => 'nullable|numeric|min:0|max:1',
        ], [
            'stem.required'        => 'Nội dung câu hỏi không được để trống.',
            'explanation.required' => 'Hướng dẫn chấm/Lời giải không được để trống.',
        ]);
    }

    /**
     * Lưu mới vào Database
     */
    public function store(array $validatedData): Question
    {
        // Dùng Transaction để nếu lỗi ở bảng sau thì bảng trước không bị lưu rác
        return DB::transaction(function () use ($validatedData) {
            
            // 1. Tạo câu hỏi (status mặc định là 0 - Chờ duyệt)
            $question = Question::create([
                'tag_name'           => $validatedData['tag_name'] ?? 'TL_' . time(), // Tạm tạo mã nếu rỗng
                'question_type_id'   => $validatedData['question_type_id'],
                'cognitive_level_id' => $validatedData['cognitive_level_id'],
                'stem'               => $validatedData['stem'],
                'difficulty_index'   => $validatedData['difficulty_index'] ?? 0.5,
                'status'             => 0, 
            ]);

            // 2. Tạo lời giải / Hướng dẫn chấm
            QuestionExplanation::create([
                'question_id' => $question->id,
                'content'     => $validatedData['explanation'],
            ]);

            return $question;
        });
    }

    /**
     * Cập nhật câu hỏi hiện tại
     */
    public function update(Question $question, array $validatedData): Question
    {
        return DB::transaction(function () use ($question, $validatedData) {
            
            // 1. Cập nhật câu hỏi & Reset trạng thái về 0 (Mất tick xanh)
            $question->update([
                'tag_name'           => $validatedData['tag_name'] ?? $question->tag_name,
                'cognitive_level_id' => $validatedData['cognitive_level_id'],
                'stem'               => $validatedData['stem'],
                'difficulty_index'   => $validatedData['difficulty_index'] ?? $question->difficulty_index,
                'status'             => 0,    // Đưa về nhãn đỏ
                'checker_id'         => null, // Xóa người duyệt cũ
                'checked_at'         => null, // Xóa ngày duyệt cũ
            ]);

            // 2. Cập nhật hoặc tạo mới lời giải (updateOrCreate)
            // Lỡ trước đó câu này chưa có lời giải thì nó tự tạo mới
            QuestionExplanation::updateOrCreate(
                ['question_id' => $question->id],
                ['content'     => $validatedData['explanation']]
            );

            return $question;
        });
    }

    /**
     * Lấy dữ liệu chi tiết
     */
    public function getDetails(Question $question): array
    {
        // Gọi relation explanation ra (để load lời giải)
        $question->load('explanation');

        return [
            'question'    => $question,
            'stem'        => $question->stem,
            'explanation' => $question->explanation ? $question->explanation->content : '',
            'type'        => 'essay'
        ];
    }
}