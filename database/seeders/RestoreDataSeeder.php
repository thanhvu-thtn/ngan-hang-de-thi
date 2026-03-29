<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RestoreDataSeeder extends Seeder
{
    public function run()
    {
        // 1. Tắt kiểm tra khóa ngoại để tránh lỗi khi xóa bảng
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Xóa 5 bảng cũ (vì file template.sql sẽ có lệnh CREATE TABLE để tự tạo lại)
        Schema::dropIfExists('objectives');
        Schema::dropIfExists('contents');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('topic_types');
        Schema::dropIfExists('subjects');

        // 3. Đọc nội dung file template.sql ở thư mục gốc và thực thi
        $sqlPath = base_path('template.sql');
        
        if (file_exists($sqlPath)) {
            $sql = file_get_contents($sqlPath);
            DB::unprepared($sql);
            
            $this->command->info('Tuyệt vời! Đã phục hồi dữ liệu thành công từ file template.sql!');
        } else {
            $this->command->error('Không tìm thấy file template.sql ở thư mục gốc!');
        }

        // 4. Bật lại kiểm tra khóa ngoại để bảo vệ database
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}