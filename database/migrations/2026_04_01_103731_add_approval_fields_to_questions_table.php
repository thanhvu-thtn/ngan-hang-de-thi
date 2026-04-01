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
        Schema::table('questions', function (Blueprint $table) {
            // Chèn ngay sau cột difficulty_index có thật của bạn
            $table->tinyInteger('status')
                  ->default(0)
                  ->comment('0: Pending, 1: Approved, 2: Rejected')
                  ->after('difficulty_index');
            
            // Chèn sau cột status
            $table->foreignId('checker_id')
                  ->nullable()
                  ->after('status')
                  ->constrained('users')
                  ->nullOnDelete();
            
            // Chèn sau cột checker_id
            $table->timestamp('checked_at')
                  ->nullable()
                  ->after('checker_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Phải drop khóa ngoại trước khi drop cột
            $table->dropForeign(['checker_id']);
            
            // Xóa các cột nếu rollback
            $table->dropColumn(['status', 'checker_id', 'checked_at']);
        });
    }
};