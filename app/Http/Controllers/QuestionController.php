<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Question; // Sau này sẽ dùng

class QuestionController extends Controller
{
    public function index()
    {
        // Tạm thời chỉ hiển thị giao diện, chưa query dữ liệu
        return view('questions.index');
    }
}