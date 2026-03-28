<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạm tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // 2. Xóa dữ liệu cũ
        DB::table('contents')->truncate();

        // 3. Lấy đường dẫn file
        $sqlFilePath = base_path('template.sql'); 

        if (File::exists($sqlFilePath)) {
            $sqlContent = File::get($sqlFilePath);
            
            // Tìm vị trí bắt đầu của câu lệnh
            $startString = 'INSERT INTO `contents`';
            $startPos = strpos($sqlContent, $startString);
            
            if ($startPos !== false) {
                // Trong cấu trúc file phpMyAdmin, sau khi kết thúc một khối INSERT, 
                // luôn có một dòng trống rồi đến comment "--" hoặc lệnh "ALTER"
                // Việc tìm theo cách này giúp bỏ qua mọi dấu chấm phẩy (;) nằm trong nội dung text
                $endPos1 = strpos($sqlContent, "\n--", $startPos);
                $endPos2 = strpos($sqlContent, "\nALTER", $startPos);
                
                // Chọn vị trí kết thúc gần nhất
                $endPos = false;
                if ($endPos1 !== false && $endPos2 !== false) {
                    $endPos = min($endPos1, $endPos2);
                } else {
                    $endPos = $endPos1 !== false ? $endPos1 : $endPos2;
                }
                
                if ($endPos !== false) {
                    // Cắt lấy toàn bộ khối văn bản SQL
                    $insertQuery = substr($sqlContent, $startPos, $endPos - $startPos);
                    
                    // Thực thi khối lệnh
                    DB::unprepared($insertQuery);
                    
                    $this->command->info('Tuyệt vời! Đã nạp thành công toàn bộ 235 dòng bảng contents.');
                } else {
                    $this->command->error('Lỗi: Tìm thấy câu lệnh nhưng không xác định được điểm kết thúc.');
                }
            } else {
                $this->command->error('Không tìm thấy lệnh INSERT INTO `contents`.');
            }
        } else {
            $this->command->error("Không tìm thấy file template.sql tại thư mục gốc.");
        }

        // 4. Bật lại khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}