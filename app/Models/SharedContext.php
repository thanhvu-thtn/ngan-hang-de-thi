<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SharedContext extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'note'];

    // Một dữ liệu dùng chung có thể chứa nhiều câu hỏi (chùm câu hỏi)
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}