<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id', 
        'reliability', 
        'validity', 
        'total_attempts', 
        'correct_attempts', 
        'change_log_path'
    ];

    // Thuộc về 1 Câu hỏi
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}