<?php

namespace App\QuestionHandlers;

class MultipleChoiceHandler implements QuestionHandlerInterface
{
    public function validateData($requestData)
    {
        // TODO: Validate có đúng 4 đáp án và 1 đáp án đúng không
    }

    public function save($questionData, $choicesData)
    {
        // TODO: Logic lưu câu hỏi MC
    }

    public function renderForm()
    {
        // TODO: Trả về form nhập MC
    }
}