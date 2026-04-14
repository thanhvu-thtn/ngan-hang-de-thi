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
            'layout_id' => $question->layout_id,
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

    /**
     * LUẬT VALIDATE RIÊNG CHO FORM CẬP NHẬT TRẮC NGHIỆM
     */
    protected function getSpecificUpdateRules(Request $request): array
    {
        return [
            'choices' => 'required|array|size:4', // Phải có đúng 4 phương án
            'choices.*.content' => 'required|string',      // Từng phương án không được rỗng
            'choices.*.ratio' => 'required|numeric|min:0.2|max:1.0', // Tỷ lệ điểm 0-100
            'correct_choice' => 'required|numeric',     // Phải có 1 nút radio được tick
            'layout_id' => 'required|exists:question_layouts,id',
        ];
    }

    /**
     * THÔNG BÁO LỖI RIÊNG
     */
    protected function getSpecificUpdateMessages(): array
    {
        return [
            'choices.required' => 'Danh sách phương án không hợp lệ.',
            'choices.size' => 'Câu hỏi trắc nghiệm phải có đúng 4 phương án.',
            'choices.*.content.required' => 'Nội dung của các phương án không được để trống.',
            'layout_id.required' => 'Vui lòng chọn một bố cục cho câu hỏi.',
            'layout_id.exists' => 'Bố cục được chọn không hợp lệ.',

            'choices.*.ratio.required' => 'Vui lòng nhập tỷ lệ chiều ngang cho phương án.',
            'choices.*.ratio.numeric' => 'Tỷ lệ chiều ngang phải là một con số.',
            'choices.*.ratio.min' => 'Tỷ lệ chiều ngang không được nhỏ hơn 0.2.',
            'choices.*.ratio.max' => 'Tỷ lệ chiều ngang không được vượt quá 1.0.',
            'correct_choice.required' => 'Vui lòng chọn một phương án làm đáp án đúng.',
        ];
    }

    /**
     * Cập nhật dữ liệu riêng cho câu hỏi Nhiều lựa chọn
     */
    protected function updateSpecificData(Question $question, array $validatedData): void
    {
        if (isset($validatedData['layout_id'])) {
            $question->layout_id = $validatedData['layout_id'];
            $question->save();
        }

        $correctChoiceIndex = $validatedData['correct_choice'];

        // 1. Lấy danh sách các phương án cũ đang có trong DB (sắp xếp theo ID cũ để đảm bảo đúng thứ tự)
        // Dùng values() để reset lại key của mảng (thành 0, 1, 2, 3...) cho khớp với index từ Form
        $existingChoices = $question->choices()->orderBy('id')->get()->values();

        // 2. Duyệt qua mảng dữ liệu mới gửi lên từ form
        foreach ($validatedData['choices'] as $index => $choiceData) {

            $isCorrect = ($index == $correctChoiceIndex);

            $updateData = [
                'content' => $choiceData['content'],
                'ratio' => $choiceData['ratio'] ?? 1.0,
                'is_correct' => $isCorrect,
            ];

            // 3. Đắp dữ liệu mới vào phương án cũ tương ứng (Dựa theo thứ tự)
            if (isset($existingChoices[$index])) {
                $existingChoices[$index]->update($updateData);
            }
            // KHÔNG CÓ LỆNH CREATE NÀO Ở ĐÂY CẢ, CẮT ĐỨT HOÀN TOÀN KHẢ NĂNG ĐẺ THÊM RÁC!
        }
    }

    // Hàm lưu dữ liệu riêng khi tạo mới câu hỏi Trắc nghiệm nhiều lựa chọn
    protected function storeSpecificData(Question $question, array $validatedData): void
    {
        $correctChoiceIndex = $validatedData['correct_choice'];

        foreach ($validatedData['choices'] as $index => $choiceData) {
            $question->choices()->create([
                'content' => $this->imageService->localizeImages($choiceData['content']),
                'is_correct' => ($index == $correctChoiceIndex),
                'ratio' => $choiceData['ratio'] ?? 1.0,
                'order' => $index,
            ]);
        }
    }
}
