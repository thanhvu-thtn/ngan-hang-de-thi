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
        
    }

    /**
     * Xuất ra PDF
     */
    public function exportPdf(Question $question)
    {
        
    }

    /**
     * Xuất ra Word (Sử dụng Pandoc như bạn tính toán)
     */
    public function exportWord(Question $question)
    {
        
    }
}