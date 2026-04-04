<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrueFalseController extends Controller
{
    //
    //hàm edit - Hiện thông số để chỉnh sửa
    public function edit($id)
    {

        return 'Đây là TrueFalseController.edit() với id = '.$id;
    }
}
