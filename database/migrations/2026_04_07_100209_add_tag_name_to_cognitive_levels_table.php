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
        // Kiểm tra xem bảng cognitive_levels đã có cột tag_name chưa
        if (!Schema::hasColumn('cognitive_levels', 'tag_name')) {
            Schema::table('cognitive_levels', function (Blueprint $table) {
                // Tùy thuộc vào code cũ của bác, thường là dòng này:
                $table->string('tag_name', 50)->nullable()->after('name'); 
            });
        }
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
