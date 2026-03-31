<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionExplanation extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'content'];

    // Lời giải thuộc về 1 câu hỏi
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}