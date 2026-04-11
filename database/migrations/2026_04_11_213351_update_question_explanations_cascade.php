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
        Schema::table('question_explanations', function (Blueprint $table) {
            // 1. Gỡ bỏ khóa ngoại cũ (Laravel tự hiểu tên khóa mặc định là: question_explanations_question_id_foreign)
            $table->dropForeign(['question_id']);

            // 2. Thêm lại khóa ngoại mới kèm theo lệnh Cascade
            $table->foreign('question_id')
                  ->references('id')
                  ->on('questions')
                  ->onDelete('cascade'); // Dòng quyết định đây bác!
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_explanations', function (Blueprint $table) {
            // Khi rollback, gỡ khóa cascade đi
            $table->dropForeign(['question_id']);

            // Trả lại khóa ngoại bình thường như cũ
            $table->foreign('question_id')
                  ->references('id')
                  ->on('questions');
        });
    }
};