<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('topic_user', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại liên kết với bảng users
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Khóa ngoại liên kết với bảng topics (giả sử bảng của bạn tên là topics)
            $table->foreignId('topic_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            // Đảm bảo 1 user không bị gán trùng 1 topic nhiều lần
            $table->unique(['user_id', 'topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topic_user');
    }
};
