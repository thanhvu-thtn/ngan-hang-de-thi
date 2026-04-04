<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MultipleChoiceController extends Controller
{
    //
    //hàm edit - Hiện thông số để chỉnh sửa
    public function edit($id)
    {

        return 'Đây là MultipleChoiceController.edit() với id = '.$id;
    }
}
