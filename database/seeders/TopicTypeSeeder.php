<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TopicTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạm thời tắt kiểm tra khóa ngoại để tránh lỗi khi xóa
        Schema::disableForeignKeyConstraints();

        // 2. Xóa dữ liệu cũ (nếu có)
        DB::table('topic_types')->truncate();

        // 3. Chèn dữ liệu từ file template.sql
        DB::table('topic_types')->insert([
            [
                'id' => 4,
                'name' => 'Cơ bản',
                'description' => 'Chương trình cốt lõi',
                'created_at' => '2026-03-28 05:39:41',
                'updated_at' => '2026-03-28 05:39:41',
            ],
            [
                'id' => 5,
                'name' => 'Nâng cao',
                'description' => 'Bao gồm cơ bản và chuyên đề nâng cao',
                'created_at' => '2026-03-28 05:39:41',
                'updated_at' => '2026-03-28 05:39:41',
            ],
            [
                'id' => 6,
                'name' => 'Chuyên',
                'description' => 'Bao gồm nâng cao và chuyên đề chuyên',
                'created_at' => '2026-03-28 05:39:41',
                'updated_at' => '2026-03-28 05:39:41',
            ],
        ]);

        // 4. Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}