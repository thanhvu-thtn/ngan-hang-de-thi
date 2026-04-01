<?php

namespace App\Services;

use App\Models\Question;
use App\QuestionHandlers\QuestionHandlerFactory; // Class dùng để gọi đúng Handler

class QuestionExportService
{
    /**
     * Render HTML để xem trước (Preview)
     */
    public function preview(Question $question)
    {
        // 1. Dùng Factory để gọi đúng Handler (VD: MultipleChoiceHandler)
        $handler = QuestionHandlerFactory::make($question->question_type_id);
        
        // 2. Lấy data sạch sẽ từ Handler
        $data = $handler->getDetails($question);
        
        // 3. Đẩy ra View
        return view('questions.preview', compact('data', 'question'));
    }

    /**
     * Xuất ra PDF
     */
    public function exportPdf(Question $question)
    {
        $handler = QuestionHandlerFactory::make($question->question_type_id);
        $data = $handler->getDetails($question);
        
        // Logic dùng Snappy / DomPDF ở đây...
    }

    /**
     * Xuất ra Word (Sử dụng Pandoc như bạn tính toán)
     */
    public function exportWord(Question $question)
    {
        $handler = QuestionHandlerFactory::make($question->question_type_id);
        $data = $handler->getDetails($question);
        
        // 1. Render data ra file HTML/Markdown tạm thời
        // 2. Chạy lệnh shell gọi Pandoc: `pandoc input.html -o output.docx`
        // 3. Trả file docx về cho người dùng tải xuống
        // 4. Xóa file tạm
    }
}