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
    ];

    /**
     * Khai báo mối quan hệ: 1 Yêu cầu cần đạt thuộc về 1 Nội dung (Content)
     */
    public function content()
    {
        return $this->belongsTo(Content::class);
    }
}