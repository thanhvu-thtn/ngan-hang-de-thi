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
        Schema::table('cognitive_levels', function (Blueprint $table) {
            // Thêm nullable() để MySQL gán NULL cho các dòng dữ liệu cũ thay vì chuỗi rỗng
            $table->string('tag_name', 50)->nullable()->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cognitive_levels', function (Blueprint $table) {
            $table->dropUnique(['tag_name']);
            $table->dropColumn('tag_name');
        });
    }
};
