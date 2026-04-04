<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShortAnswerController extends Controller
{
    //index
    public function index()
    {
        //return view('questions.short_answer.index');
    }

    //create
    public function create()
    {        //return view('questions.short_answer.create');
    }

    //store
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        // return
    } 

    //show
    public function show($id)
    {   // Lấy thông tin câu hỏi theo ID
         return "Đây là SortAnswerController, hiển thị câu hỏi có ID: " . $id;
    }
    //hàm edit - Hiện thông số để chỉnh sửa
    public function edit($id)
    {

        return 'Đây là ShortAnswerController.edit() với id = '.$id;
    }

}
