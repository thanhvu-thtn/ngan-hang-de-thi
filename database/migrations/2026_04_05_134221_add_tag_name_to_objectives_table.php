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
        Schema::table('objectives', function (Blueprint $table) {
            // Thêm nullable() để không bị lỗi với các dữ liệu cũ đã có trong DB
            $table->string('tag_name')->nullable()->unique()->after('id')->comment('Mã định danh gợi nhớ của mục tiêu/yêu cầu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('objectives', function (Blueprint $table) {
            $table->dropColumn('tag_name');
        });
    }
};