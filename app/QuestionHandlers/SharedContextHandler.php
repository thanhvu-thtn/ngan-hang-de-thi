<?php

namespace App\QuestionHandlers;

class SharedContextHandler implements QuestionHandlerInterface
{
    // Class đặc biệt này sẽ quản lý 'shared_contexts' và 
    // một mảng các câu hỏi con (ví dụ: 4 câu TF) thuộc về nó.

    public function validateData($requestData)
    {
        // TODO: Validate đoạn văn dùng chung và mảng các câu hỏi con
    }

    public function save($questionData, $choicesData)
    {
        // TODO: Logic lưu Shared Context và lặp qua để lưu các câu hỏi con
    }

    public function renderForm()
    {
        // TODO: Trả về form nhập câu hỏi chùm
    }
}