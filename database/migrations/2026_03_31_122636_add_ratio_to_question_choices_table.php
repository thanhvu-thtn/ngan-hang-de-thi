<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('question_choices', function (Blueprint $table) {
            // Thêm cột ratio dạng số thập phân (tương tự như bên layout)
            $table->decimal('ratio', 2, 1)->default(1.0)->after('is_correct');
        });
    }

    public function down()
    {
        Schema::table('question_choices', function (Blueprint $table) {
            $table->dropColumn('ratio');
        });
    }
};