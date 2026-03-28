<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Thêm thư viện Schema

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạm thời tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // 2. Xóa dữ liệu cũ một cách an toàn
        DB::table('subjects')->truncate();

        // 3. Chèn dữ liệu từ file template.sql
        DB::table('subjects')->insert([
            [
                'id' => 1,
                'name' => 'Vật Lí',
                'description' => null,
                'created_at' => '2026-03-28 05:41:40',
                'updated_at' => '2026-03-28 05:41:40',
            ],
            [
                'id' => 2,
                'name' => 'Toán học',
                'description' => null,
                'created_at' => '2026-03-28 05:41:40',
                'updated_at' => '2026-03-28 05:41:40',
            ],
            [
                'id' => 3,
                'name' => 'Hóa học',
                'description' => null,
                'created_at' => '2026-03-28 05:41:40',
                'updated_at' => '2026-03-28 05:41:40',
            ],
        ]);

        // 4. Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}