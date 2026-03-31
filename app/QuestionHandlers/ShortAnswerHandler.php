<?php

namespace App\QuestionHandlers;

class ShortAnswerHandler implements QuestionHandlerInterface
{
    public function validateData($requestData)
    {
        // TODO: Validate
    }

    public function save($questionData, $choicesData)
    {
        // TODO: Logic lưu câu hỏi Trả lời ngắn
    }

    public function renderForm()
    {
        // TODO: Trả về form nhập Trả lời ngắn
    }
}