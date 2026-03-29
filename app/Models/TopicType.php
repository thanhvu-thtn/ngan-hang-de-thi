<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicType extends Model
{
    use HasFactory;

    protected $table = 'topic_types';

    // Đã bổ sung description
    protected $fillable = ['name', 'description']; 
}