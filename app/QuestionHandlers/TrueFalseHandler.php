<?php

namespace App\QuestionHandlers;

class TrueFalseHandler implements QuestionHandlerInterface
{
    // Đúng như bạn nói, loại này cực đơn giản, chỉ cần lưu 1 Question Choice 
    // và cờ is_correct (1 là mệnh đề đúng, 0 là mệnh đề sai).

    public function validateData($requestData)
    {
        // TODO: Validate
    }

    public function save($questionData, $choicesData)
    {
        // TODO: Logic lưu câu hỏi TF
    }

    public function renderForm()
    {
        // TODO: Trả về form nhập TF
    }
}