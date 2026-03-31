<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            // 1. Xóa 2 cột layout cũ
            $table->dropColumn(['layout_type', 'layout_ratio']);

            // 2. Thêm cột khóa ngoại layout_id (đặt sau cột shared_context_id cho gọn)
            $table->foreignId('layout_id')
                  ->nullable()
                  ->after('shared_context_id')
                  ->constrained('question_layouts')
                  ->nullOnDelete(); // Nếu xóa layout, set column này thành null (không mất câu hỏi)

            // 3. Thêm cột name (Tên/Mô tả ngắn của câu hỏi) để mốt làm màn hình danh sách
            $table->string('name')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            // Phục hồi lại 2 cột cũ nếu lỡ có rollback
            $table->string('layout_type')->nullable();
            $table->string('layout_ratio')->nullable();

            // Xóa khóa ngoại và các cột mới thêm
            $table->dropForeign(['layout_id']);
            $table->dropColumn(['layout_id', 'name']);
        });
    }
};