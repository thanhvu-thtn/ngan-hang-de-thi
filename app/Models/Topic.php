<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $table = 'topics';

    protected $fillable = [
        'subject_id', 
        'topic_type_id', 
        'name', 
        'grade', 
        'order', 
        'total_periods'
    ];

    // Mối quan hệ: 1 Chuyên đề thuộc về 1 Môn học
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    // Mối quan hệ: 1 Chuyên đề thuộc về 1 Loại chuyên đề
    public function topicType()
    {
        return $this->belongsTo(TopicType::class, 'topic_type_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
    }

    /**
     * Lấy danh sách giáo viên được phân công chuyên đề này.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'topic_user', 'topic_id', 'user_id')->withTimestamps();
    }
}