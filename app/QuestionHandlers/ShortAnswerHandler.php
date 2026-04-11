<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use Illuminate\Http\Request;

class ShortAnswerHandler extends BaseQuestionHandler
{
    public function validateData(Request $request): array
    {
        // TODO: Validate dữ liệu từ Form gửi lên
        return [];
    }

    public function store(array $data): Question
    {
        // TODO: Logic lưu câu hỏi Trả lời ngắn
        return new Question;
    }

    public function update(Question $question, array $validatedData): Question
    {
        // TODO: Logic cập nhật câu hỏi
        return $question;
    }


    public function destroy(Question $question): void
    {
        // TODO: Xóa câu hỏi
    }

    // -----------------------------------------------------------------------------
    //
    // CODE MỚI - KẾ THỪA TỪ BASEQUESTIONHANDLER (Có thể có thêm các hàm phụ trợ nếu cần)
    // -----------------------------------------------------------------------------
    /**
     * Hàm dùng chung để xóa câu hỏi (bao gồm cả xóa các lựa chọn liên quan nếu có)
     */

    /**
     * Validate dữ liệu riêng khi Import từ file cho câu hỏi Trả lời ngắn (SA)
     */
    /**
     * Validate dữ liệu riêng khi Import từ file cho câu hỏi Trả lời ngắn (SA)
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
                'errors' => ['Câu hỏi SA phải có 1 lựa chọn làm đáp số.'],
                'warnings' => $warnings,
            ];
        } elseif ($totalChoices > 1) {
            return [
                'is_valid' => false,
                'errors' => ['Câu hỏi SA chỉ có duy nhất 1 lựa chọn làm đáp số.'],
                'warnings' => $warnings,
            ];
        }

        // 2. Kiểm tra định dạng của đáp số
        $rawContent = $choices[0]['content'] ?? '';
        $answerText = trim(strip_tags(html_entity_decode($rawContent)));
        $len = strlen($answerText);

        // a. Chiều dài 1 -> 4 ký tự
        if ($len === 0 || $len > 4) {
            $isValid = false;
        }
        // b. Ép định dạng số chuẩn Việt Nam bằng Regex
        // Giải thích Regex:
        // ^-?         : Có thể có hoặc không có dấu trừ ở đầu tiên
        // [0-9]+      : Bắt buộc phải có ít nhất 1 chữ số trước dấu phẩy
        // (,[0-9]+)?$ : Khối thập phân (nếu có) phải có dấu phẩy và ít nhất 1 chữ số đi theo sau, nằm ở cuối chuỗi
        elseif (! preg_match('/^-?[0-9]+(,[0-9]+)?$/', $answerText)) {
            $isValid = false;
        }

        if (! $isValid) {
            $errors[] = 'Đáp số của câu hỏi SA không đúng qui định (Chỉ chứa tối đa 4 ký tự, là số nguyên hoặc số thập phân hợp lệ chuẩn VN).';
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    protected function getSpecificDetails(Question $question): array
    {
        // Lấy lựa chọn duy nhất (chính là chuỗi câu trả lời ngắn)
        $choice = $question->choices()->first();

        return [
            // Trả về một key 'answer' để hiển thị trực tiếp lên Blade
            'answer' => $choice ? $choice->content : 'Chưa có đáp án',
        ];
    }

    /**
     * Lưu dữ liệu đặc thù của câu hỏi Trả lời ngắn (SA) vào Database
     */
    protected function storeSpecificImportData(Question $question, array $questionData): void
    {
        // Do ở bước validate ta đã chặn lỗi, nên chắc chắn lúc này có 1 choice
        $rawContent = $questionData['choices'][0]['content'] ?? '';

        // Làm sạch chuỗi giống như lúc validate
        $answerText = trim(strip_tags(html_entity_decode($rawContent)));

        // Lưu đáp án duy nhất này vào bảng question_choices
        $question->choices()->create([
            'content' => $answerText,
            'is_correct' => true, // Đối với SA, đáp án nhập vào mặc định là đáp án đúng
            'order' => 1,
        ]);
    }

    /**
     * LUẬT VALIDATE RIÊNG CHO FORM CẬP NHẬT TRẢ LỜI NGẮN
     */
    protected function getSpecificUpdateRules(\Illuminate\Http\Request $request): array
    {
        return [
            'sa_answer' => [
                'required',
                'string',
                'max:4',
                'regex:/^-?[0-9]+(,[0-9]+)?$/' // Phải là số, có thể có dấu âm, có tối đa 1 dấu phẩy
            ],
        ];
    }

    /**
     * THÔNG BÁO LỖI RIÊNG 
     */
    protected function getSpecificUpdateMessages(): array
    {
        return [
            'sa_answer.required' => 'Vui lòng nhập đáp án chính xác cho câu hỏi.',
            'sa_answer.max'      => 'Đáp án chỉ được chứa tối đa 4 ký tự.',
            'sa_answer.regex'    => 'Đáp án không đúng định dạng (chỉ chứa số, dấu trừ ở đầu và tối đa 1 dấu phẩy).',
        ];
    }

    protected function updateSpecificData(Question $question, array $validatedData): void
    {
        // Trả lời ngắn (SA) luôn chỉ có 1 record trong bảng choices để chứa đáp án
        $choice = $question->choices()->first();

        if ($choice) {
            $choice->update([
                'content' => $validatedData['sa_answer']
            ]);
        } else {
            // Đề phòng trường hợp trước đó bị lỗi mất data, tự động tạo lại
            $question->choices()->create([
                'content'    => $validatedData['sa_answer'],
                'is_correct' => true,
                'order'      => 1,
            ]);
        }
    }
}
