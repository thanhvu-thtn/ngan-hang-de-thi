<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionLayout extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'ratio'];

    // Ép kiểu dữ liệu khi lấy từ Database ra (giúp ratio luôn là số thực float)
    protected $casts = [
        'ratio' => 'float',
    ];

    // Cấu hình giá trị mặc định khi new Model (Khớp với yêu cầu của bạn)
    protected $attributes = [
        'code' => '1x4',
        'ratio' => 1.0,
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'layout_id');
    }
}