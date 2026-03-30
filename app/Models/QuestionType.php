<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'expected_choices_count'];

    // Một loại câu hỏi có nhiều câu hỏi
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}