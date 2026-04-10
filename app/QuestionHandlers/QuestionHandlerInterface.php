<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use Illuminate\Http\Request;

interface QuestionHandlerInterface
{
    /**
     * Xác thực dữ liệu đầu vào.
     */
    public function validateData(Request $request): array;

    /**
     * Lưu mới câu hỏi.
     */
   public function store(array $data): Question;

    /**
     * Cập nhật câu hỏi.
     */
    public function update(Question $question, array $validatedData): Question;

    /**
     * Lấy dữ liệu chi tiết của câu hỏi (dùng để hiển thị hoặc nhét vào Service xuất file).
     */
    public function getDetails(Question $question): array;
    // Thêm dòng này vào cùng với các hàm validateData, store, update...
    public function destroy(Question $question): void;
    // THÊM DÒNG NÀY: Kiểm tra dữ liệu
    public function validateImportData(array $questionData): array;
}