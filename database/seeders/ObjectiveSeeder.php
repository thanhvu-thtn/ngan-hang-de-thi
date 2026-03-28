<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class ObjectiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạm tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // 2. Xóa dữ liệu cũ
        DB::table('objectives')->truncate();

        // 3. Lấy đường dẫn file template.sql
        $sqlFilePath = base_path('template.sql'); 

        if (File::exists($sqlFilePath)) {
            $sqlContent = File::get($sqlFilePath);
            
            // Tìm vị trí bắt đầu của câu lệnh chèn vào bảng objectives
            $startString = 'INSERT INTO `objectives`';
            $startPos = strpos($sqlContent, $startString);
            
            if ($startPos !== false) {
                // Tìm vị trí kết thúc: là phần comment kế tiếp hoặc lệnh ALTER TABLE ở cuối file
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
                    
                    $this->command->info('Tuyệt vời! Đã nạp thành công toàn bộ 447 dòng bảng objectives.');
                } else {
                    $this->command->error('Lỗi: Tìm thấy câu lệnh nhưng không xác định được điểm kết thúc.');
                }
            } else {
                $this->command->error('Không tìm thấy lệnh INSERT INTO `objectives` trong file SQL.');
            }
        } else {
            $this->command->error("Không tìm thấy file template.sql tại thư mục gốc.");
        }

        // 4. Bật lại khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}