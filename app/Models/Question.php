<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Cập nhật lại mảng fillable theo cấu trúc mới
    protected $fillable = [
        'tag_name', // Thêm dòng này
        'name', 
        'question_type_id', 
        'cognitive_level_id', 
        'shared_context_id', 
        'layout_id', 
        'stem', 
        'difficulty_index',
        'status',       // Mới thêm
        'checker_id',   // Mới thêm
        'checked_at',   // Mới thêm
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

    // Thuộc về 1 Cấu hình hiển thị - MỚI THÊM
    public function layout()
    {
        return $this->belongsTo(QuestionLayout::class, 'layout_id');
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

    // Một câu hỏi có tối đa 1 lời giải (hasOne)
    public function explanation()
    {
        return $this->hasOne(QuestionExplanation::class);
    }

    // 3. Mối quan hệ: Người thẩm định câu hỏi
    public function checker()
    {
        return $this->belongsTo(User::class, 'checker_id');
    }

    // 4. Các hàm Scope hỗ trợ truy vấn nhanh sau này
    public function scopePending($query)
    {
        return $query->where('status', 0); // Chờ duyệt
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 1); // Đạt chuẩn
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 2); // Cần sửa lại
    }

    
}