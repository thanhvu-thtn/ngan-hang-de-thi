<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shared_contexts', function (Blueprint $table) {
            // Thêm tag_name sau cột id, bắt buộc unique để dễ tìm
            $table->string('tag_name')->unique()->after('id')->comment('Mã định danh gợi nhớ của ngữ cảnh chung');
        });
    }

    public function down(): void
    {
        Schema::table('shared_contexts', function (Blueprint $table) {
            $table->dropColumn('tag_name');
        });
    }
};