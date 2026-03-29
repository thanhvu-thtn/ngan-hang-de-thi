<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Khai báo tên bảng (tùy chọn, nhưng nên có cho chắc chắn)
    protected $table = 'subjects';

    // Các cột được phép gán dữ liệu hàng loạt (Mass Assignment)
    protected $fillable = ['name']; 
}