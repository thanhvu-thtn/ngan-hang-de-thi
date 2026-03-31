<?php

namespace App\QuestionHandlers;

interface QuestionHandlerInterface
{
    // Hàm validate dữ liệu đầu vào từ Form
    public function validateData($requestData);

    // Hàm lưu câu hỏi và đáp án vào Database
    public function save($questionData, $choicesData);

    // Hàm trả về View (form nhập liệu) tương ứng cho loại câu hỏi này
    public function renderForm();
}