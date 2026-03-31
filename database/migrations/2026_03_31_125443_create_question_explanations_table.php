<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_explanations', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết với bảng questions, thiết lập ON DELETE CASCADE
            // Để khi xóa câu hỏi thì lời giải cũng tự động "bay" theo, không để lại rác
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            
            // Nội dung lời giải (dùng longText để chứa thoải mái văn bản, công thức, thẻ HTML)
            $table->longText('content');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_explanations');
    }
};