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
        Schema::create('question_layouts', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Tên: 1 Cột, 2 Cột song song...
        $table->string('code')->unique(); // Mã: 1x4, 2x2...
        $table->string('ratio')->nullable(); // Tỉ lệ: 5:5, 6:4...
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_layouts');
    }
};
