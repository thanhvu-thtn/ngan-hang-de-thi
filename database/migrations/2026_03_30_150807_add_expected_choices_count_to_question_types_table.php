<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('question_types', function (Blueprint $table) {
            // Thêm cột sau cột 'code', mặc định là 4
            $table->integer('expected_choices_count')->default(4)->after('code')
                ->comment('Số lượng lựa chọn/đáp án chuẩn bắt buộc phải có');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('question_types', function (Blueprint $table) {
            $table->dropColumn('expected_choices_count');
        });
    }
};
