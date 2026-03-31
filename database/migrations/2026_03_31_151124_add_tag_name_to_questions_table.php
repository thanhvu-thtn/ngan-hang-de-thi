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
            // Thêm trường tag_name, duy nhất, đặt sau cột id
            $table->string('tag_name')->unique()->after('id')->comment('Mã định danh gợi nhớ của câu hỏi');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('tag_name');
        });
    }
};
