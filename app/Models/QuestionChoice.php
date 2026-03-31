<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    // Thêm 'ratio' vào fillable
    protected $fillable = ['question_id', 'content', 'is_correct', 'order', 'ratio'];

    // Ép kiểu dữ liệu
    protected $casts = [
        'is_correct' => 'boolean',
        'ratio' => 'float', // Ép tự động về float khi lấy từ DB
    ];

    // Cấu hình giá trị mặc định khi new Model
    protected $attributes = [
        'is_correct' => false,
        'ratio' => 1.0,
    ];

    // Thuộc về 1 Câu hỏi
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}