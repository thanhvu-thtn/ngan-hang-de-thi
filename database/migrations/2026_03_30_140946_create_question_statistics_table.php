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
        Schema::create('question_statistics', function (Blueprint $table) {
            $table->id();
        // Một câu hỏi chỉ có 1 record thống kê nên dùng unique()
        $table->foreignId('question_id')->unique()->constrained('questions')->cascadeOnDelete();
        
        $table->float('reliability')->nullable();
        $table->float('validity')->nullable();
        $table->integer('total_attempts')->default(0);
        $table->integer('correct_attempts')->default(0);
        $table->string('change_log_path')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_statistics');
    }
};
