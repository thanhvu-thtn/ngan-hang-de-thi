<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use Illuminate\Http\Request;

class TrueFalseHandler extends BaseQuestionHandler
{
    public function validateData(Request $request): array
    {
        // TODO: Validate dữ liệu từ Form gửi lên
        return [];
    }

    public function store(array $data): Question
    {
        // TODO: Logic lưu câu hỏi Đúng/Sai (chỉ cần lưu 1 Question Choice và cờ is_correct)
        return new Question;
    }

    public function update(Question $question, array $validatedData): Question
    {
        // TODO: Logic cập nhật câu hỏi Đúng/Sai
        return $question;
    }

    public function destroy(Question $question): void
    {
        // TODO: Xóa câu hỏi Đúng/Sai
    }

    // -----------------------------------------------------------------------------
    //
    // CODE MỚI - KẾ THỪA TỪ BASEQUESTIONHANDLER (Có thể có thêm các hàm phụ trợ nếu cần)
    // -----------------------------------------------------------------------------
    /**
     * Hàm dùng chung để xóa câu hỏi (bao gồm cả xóa các lựa chọn liên quan nếu có)
     */

    /**
     * Validate dữ liệu riêng khi Import từ file
     */
    /**
     * Validate dữ liệu riêng khi Import từ file cho câu hỏi Đúng/Sai (TF)
     */
    protected function validateSpecificImportData(array $questionData): array
    {
        $isValid = true;
        $errors = [];
        $warnings = [];

        $choices = $questionData['choices'] ?? [];
        $totalChoices = count($choices);

        // 1. Kiểm tra số lượng lựa chọn
        if ($totalChoices === 0) {
            return [
                'is_valid' => false,
                'errors' => ['Câu hỏi TF phải có 1 lựa chọn làm đáp án.'],
                'warnings' => $warnings,
            ];
        } elseif ($totalChoices > 1) {
            return [
                'is_valid' => false,
                'errors' => ['Câu hỏi TF chỉ có duy nhất 1 lựa chọn làm đáp án.'],
                'warnings' => $warnings,
            ];
        }

        // 2. Làm sạch và chuẩn hóa thành chữ in hoa (Dùng mb_strtoupper để hỗ trợ tiếng Việt)
        $rawContent = $choices[0]['content'] ?? '';
        $answerText = mb_strtoupper(trim(strip_tags(html_entity_decode($rawContent))), 'UTF-8');

        // 3. Kiểm tra các giá trị được phép
        $allowedValues = ['Đ', 'S', 'T', 'F', 'ĐÚNG', 'SAI', 'TRUE', 'FALSE'];

        if (! in_array($answerText, $allowedValues)) {
            $isValid = false;
            $errors[] = "Đáp án của câu hỏi TF không hợp lệ. Chỉ chấp nhận các giá trị: 'Đ', 'S', 'T', 'F', 'Đúng', 'Sai', 'True', 'False'.";
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Lưu dữ liệu đặc thù của câu hỏi Đúng/Sai (TF) vào Database
     */
    protected function storeSpecificImportData(Question $question, array $questionData): void
    {
        // Lấy và chuẩn hóa chuỗi như lúc validate
        $rawContent = $questionData['choices'][0]['content'] ?? '';
        $answerText = mb_strtoupper(trim(strip_tags(html_entity_decode($rawContent))), 'UTF-8');

        // Gom nhóm các từ khóa mang ý nghĩa "Đúng"
        $trueValues = ['Đ', 'T', 'ĐÚNG', 'TRUE'];
        
        // Chuẩn hóa thành 1 chữ duy nhất trước khi lưu vào DB để hệ thống nhất quán
        $finalAnswer = in_array($answerText, $trueValues) ? 'Đúng' : 'Sai';

        // Lưu đáp án vào bảng choices
        $question->choices()->create([
            'content'    => $finalAnswer,
            'is_correct' => true, // Mặc định đáp án import vào là đáp án đúng
            'order'      => 1,
        ]);
    }

    protected function getSpecificDetails(Question $question): array
    {
        // Lấy lựa chọn duy nhất (đáp án True hoặc False)
        $choice = $question->choices()->first();

        return [
            // Trả về một key 'answer' để hiển thị trực tiếp lên Blade
            'answer' => $choice ? $choice->content : 'Chưa có đáp án',
        ];
    }
}
