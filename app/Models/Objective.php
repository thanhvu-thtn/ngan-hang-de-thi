<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'description',
        'order',
        'tag_name'
    ];

    /**
     * Khai báo mối quan hệ: 1 Yêu cầu cần đạt thuộc về 1 Nội dung (Content)
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    // Bổ sung vào trong class Objective
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'objective_question');
    }
}