<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use Illuminate\Http\Request;

class MultipleChoiceHandler extends BaseQuestionHandler
{
    public function validateData(Request $request): array
    {
        // TODO: Validate dữ liệu từ Form gửi lên (có đúng 4 đáp án và 1 đáp án đúng không)
        return [];
    }

    public function store(array $data): Question
    {
        // TODO: Logic lưu mới câu hỏi Trắc nghiệm nhiều lựa chọn
        return new Question;
    }

    public function update(Question $question, array $validatedData): Question
    {
        // TODO: Logic cập nhật câu hỏi
        return $question;
    }


    public function destroy(Question $question): void
    {
        // TODO: Logic xóa câu hỏi và các lựa chọn kèm theo
    }

    // -----------------------------------------------------------------------------
    //
    // CODE MỚI - KẾ THỪA TỪ BASEQUESTIONHANDLER (Có thể có thêm các hàm phụ trợ nếu cần)
    // -----------------------------------------------------------------------------
    /**
     * Hàm dùng chung để xóa câu hỏi (bao gồm cả xóa các lựa chọn liên quan nếu có)
     */

    /**
     * Validate dữ liệu riêng khi Import từ file cho câu hỏi Trắc nghiệm nhiều lựa chọn
     */
    protected function validateSpecificImportData(array $questionData): array
    {
        $isValid = true;
        $errors = [];
        $warnings = [];

        $choices = $questionData['choices'] ?? [];
        $totalChoices = count($choices);

        // 1. Kiểm tra số lượng lựa chọn (Bắt buộc phải là 4)
        if ($totalChoices !== 4) {
            $isValid = false;
            $errors[] = "Câu hỏi MC có đúng 4 lựa chọn nhưng bạn đã nhập {$totalChoices} lựa chọn.";
        }

        // 2. Đếm số lượng đáp án đúng
        $correctCount = 0;
        foreach ($choices as $choice) {
            if (! empty($choice['is_correct'])) {
                $correctCount++;
            }
        }

        // 3. Kiểm tra logic đáp án đúng (Bắt buộc có duy nhất 1)
        if ($correctCount === 0) {
            $isValid = false;
            $errors[] = 'Câu MC chỉ chấp nhận duy nhất 1 đáp án đúng nhưng không thấy bạn nhập đáp án đúng.';
        } elseif ($correctCount > 1) {
            $isValid = false;
            $errors[] = "Câu MC chỉ chấp nhận duy 1 đáp án đúng mà bạn nhập có {$correctCount} đáp án đúng.";
        }

        return [
            'is_valid' => $isValid,
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    protected function storeSpecificImportData(Question $question, array $questionData): void
    {
        // 1. Gán layout mặc định cho MC (ID = 4: 4 hàng, 1 cột)
        $question->update(['layout_id' => 4]);

        // 2. Lưu các lựa chọn (choices)
        if (! empty($questionData['choices'])) {
            foreach ($questionData['choices'] as $choice) {
                $question->choices()->create([
                    'content' => $choice['content'],
                    'is_correct' => $choice['is_correct'] ?? false,
                    'order' => $choice['order'] ?? 0,
                ]);
            }
        }
    }

    // / Hàm dùng chung để lấy chi tiết câu hỏi khi Show hoặc Edit
    protected function getSpecificDetails(Question $question): array
    {
        // Load các lựa chọn của câu hỏi MC
        return [
            'choices' => $question->choices()->orderBy('order')->get()->map(function ($choice) {
                return [
                    'id' => $choice->id,
                    'content' => $choice->content,
                    'is_correct' => $choice->is_correct,
                    'order' => $choice->order,
                    'ratio' => $choice->ratio,
                ];
            })->toArray(),
        ];
    }
}
