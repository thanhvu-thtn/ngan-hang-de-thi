<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('question_layouts', function (Blueprint $table) {
            // Đổi từ string sang decimal(2,1) và set mặc định là 1.0
            $table->decimal('ratio', 2, 1)->default(1.0)->change();
        });
    }

    public function down()
    {
        Schema::table('question_layouts', function (Blueprint $table) {
            // Phục hồi lại thành string nếu rollback
            $table->string('ratio')->nullable()->change();
        });
    }
};