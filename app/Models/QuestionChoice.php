<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'content', 'is_correct', 'order'];

    // Cast is_correct về dạng boolean tự động
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Thuộc về 1 Câu hỏi
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}