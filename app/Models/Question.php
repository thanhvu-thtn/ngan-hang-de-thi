<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type_id', 
        'cognitive_level_id', 
        'shared_context_id', 
        'stem', 
        'difficulty_index', 
        'layout_type', 
        'layout_ratio'
    ];

    // Thuộc về 1 Loại câu hỏi
    public function type()
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    // Thuộc về 1 Mức độ nhận thức
    public function cognitiveLevel()
    {
        return $this->belongsTo(CognitiveLevel::class);
    }

    // Thuộc về 1 Dữ liệu dùng chung (nếu có)
    public function sharedContext()
    {
        return $this->belongsTo(SharedContext::class);
    }

    // Có nhiều Lựa chọn/Đáp án
    public function choices()
    {
        return $this->hasMany(QuestionChoice::class);
    }

    // Có 1 bản ghi Thống kê
    public function statistic()
    {
        return $this->hasOne(QuestionStatistic::class);
    }

    // Thuộc nhiều Yêu cầu cần đạt (Quan hệ N-N)
    public function objectives()
    {
        return $this->belongsToMany(Objective::class, 'objective_question');
    }
}