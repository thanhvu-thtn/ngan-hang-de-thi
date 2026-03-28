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
       // 1. Bảng Môn học 
    Schema::create('subjects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('description')->nullable();
        $table->timestamps();
    });

    // 2. Bảng Loại chuyên đề (Loại chương trình) 
    Schema::create('topic_types', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('description')->nullable();
        $table->timestamps();
    });

    // 3. Bảng Chuyên đề 
    Schema::create('topics', function (Blueprint $table) {
        $table->id();
        $table->foreignId('subject_id')->constrained('subjects');
        $table->foreignId('topic_type_id')->constrained('topic_types')->onDelete('cascade');
        $table->string('name');
        $table->tinyInteger('grade'); // 10, 11, 12 
        $table->integer('order')->default(1);
        $table->integer('total_periods');
        $table->timestamps();
    });

    // 4. Bảng Nội dung 
    Schema::create('contents', function (Blueprint $table) {
        $table->id();
        $table->integer('order')->default(1);
        $table->foreignId('topic_id')->constrained('topics')->onDelete('cascade');
        $table->string('name');
        $table->integer('periods');
        $table->timestamps();
    });

    // 5. Bảng Yêu cầu cần đạt (Objectives) 
    Schema::create('objectives', function (Blueprint $table) {
        $table->id();
        $table->foreignId('content_id')->constrained('contents')->onDelete('cascade');
        $table->text('description');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('objectives');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('topic_types');
        Schema::dropIfExists('subjects');
    }
};
