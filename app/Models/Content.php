<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    // Các trường cho phép thêm/sửa hàng loạt
    protected $fillable = [
        'topic_id',
        'name',
        'order',
        'periods',
    ];

    /**
     * Khai báo mối quan hệ: 1 Nội dung (Content) thuộc về 1 Chuyên đề (Topic)
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Khai báo mối quan hệ: 1 Nội dung (Content) có nhiều Yêu cầu cần đạt (Objective)
     */
    public function objectives()
    {
        return $this->hasMany(Objective::class);
    }
}