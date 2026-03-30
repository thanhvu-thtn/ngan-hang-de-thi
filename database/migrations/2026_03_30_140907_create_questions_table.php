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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_type_id')->constrained('question_types')->cascadeOnDelete();
            $table->foreignId('cognitive_level_id')->constrained('cognitive_levels')->cascadeOnDelete();
            $table->foreignId('shared_context_id')->nullable()->constrained('shared_contexts')->nullOnDelete();

            $table->text('stem');
            $table->float('difficulty_index')->default(0);
            $table->string('layout_type')->nullable();
            $table->string('layout_ratio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
