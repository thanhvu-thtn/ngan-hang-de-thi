<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CognitiveLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level_weight'];

    // Một mức độ nhận thức có nhiều câu hỏi
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}