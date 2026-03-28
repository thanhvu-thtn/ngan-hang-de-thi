<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tạm thời tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // 2. Xóa dữ liệu cũ (nếu có)
        DB::table('topics')->truncate();

        // 3. Chèn dữ liệu từ file template.sql
        DB::table('topics')->insert([
            ['id' => 1, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Động học', 'grade' => 10, 'order' => 2, 'total_periods' => 16, 'created_at' => '2026-03-22 09:37:37', 'updated_at' => '2026-03-24 07:44:42'],
            ['id' => 2, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Mở đầu', 'grade' => 10, 'order' => 1, 'total_periods' => 7, 'created_at' => '2026-03-22 09:38:20', 'updated_at' => '2026-03-24 07:44:37'],
            ['id' => 5, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Mở đầu', 'grade' => 10, 'order' => 1, 'total_periods' => 4, 'created_at' => '2026-03-23 13:26:02', 'updated_at' => '2026-03-24 07:44:30'],
            ['id' => 6, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Dao động', 'grade' => 11, 'order' => 1, 'total_periods' => 14, 'created_at' => '2026-03-23 20:25:01', 'updated_at' => '2026-03-25 04:47:32'],
            ['id' => 7, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Sóng', 'grade' => 11, 'order' => 2, 'total_periods' => 16, 'created_at' => '2026-03-24 06:17:15', 'updated_at' => '2026-03-25 04:47:50'],
            ['id' => 8, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Động lực học', 'grade' => 10, 'order' => 3, 'total_periods' => 18, 'created_at' => '2026-03-24 06:18:04', 'updated_at' => '2026-03-24 07:44:47'],
            ['id' => 9, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Năng lượng, Công, Công suất', 'grade' => 10, 'order' => 4, 'total_periods' => 10, 'created_at' => '2026-03-24 06:18:24', 'updated_at' => '2026-03-24 07:44:53'],
            ['id' => 10, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Động lượng', 'grade' => 10, 'order' => 5, 'total_periods' => 6, 'created_at' => '2026-03-24 06:18:37', 'updated_at' => '2026-03-24 07:44:58'],
            ['id' => 11, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Chuyển động tròn', 'grade' => 10, 'order' => 6, 'total_periods' => 4, 'created_at' => '2026-03-24 06:18:50', 'updated_at' => '2026-03-24 07:45:03'],
            ['id' => 12, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Biến dạng của vật rắn. Áp suất chất lỏng', 'grade' => 10, 'order' => 7, 'total_periods' => 9, 'created_at' => '2026-03-24 06:19:07', 'updated_at' => '2026-03-24 07:45:08'],
            ['id' => 21, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Vật lí trong một số ngành nghề', 'grade' => 10, 'order' => 1, 'total_periods' => 10, 'created_at' => '2026-03-25 04:36:25', 'updated_at' => '2026-03-25 04:36:25'],
            ['id' => 22, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Trái đất và bầu trời', 'grade' => 10, 'order' => 2, 'total_periods' => 10, 'created_at' => '2026-03-25 04:36:39', 'updated_at' => '2026-03-25 04:46:27'],
            ['id' => 23, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Vật lí với giáo dục bảo vệ môi trường', 'grade' => 10, 'order' => 3, 'total_periods' => 15, 'created_at' => '2026-03-25 04:36:50', 'updated_at' => '2026-03-25 04:46:42'],
            ['id' => 24, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Cơ học chất điểm', 'grade' => 10, 'order' => 1, 'total_periods' => 20, 'created_at' => '2026-03-25 04:37:05', 'updated_at' => '2026-03-27 00:52:04'],
            ['id' => 25, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Động học vật rắn', 'grade' => 10, 'order' => 2, 'total_periods' => 15, 'created_at' => '2026-03-25 04:37:16', 'updated_at' => '2026-03-27 00:52:11'],
            ['id' => 26, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Động lực học vật rắn', 'grade' => 10, 'order' => 3, 'total_periods' => 20, 'created_at' => '2026-03-25 04:37:34', 'updated_at' => '2026-03-27 00:52:19'],
            ['id' => 27, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Hệ quy chiếu phi quán tính', 'grade' => 10, 'order' => 4, 'total_periods' => 15, 'created_at' => '2026-03-25 04:37:54', 'updated_at' => '2026-03-27 00:52:27'],
            ['id' => 28, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Thực hành', 'grade' => 10, 'order' => 5, 'total_periods' => 15, 'created_at' => '2026-03-25 04:38:09', 'updated_at' => '2026-03-27 00:52:35'],
            ['id' => 29, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Điện trường', 'grade' => 11, 'order' => 3, 'total_periods' => 18, 'created_at' => '2026-03-25 04:38:29', 'updated_at' => '2026-03-25 04:47:59'],
            ['id' => 30, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Dòng điện, Mạch điện', 'grade' => 11, 'order' => 4, 'total_periods' => 14, 'created_at' => '2026-03-25 04:38:48', 'updated_at' => '2026-03-25 04:48:15'],
            ['id' => 39, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Trường hấp dẫn', 'grade' => 11, 'order' => 1, 'total_periods' => 15, 'created_at' => '2026-03-25 04:40:48', 'updated_at' => '2026-03-25 04:48:32'],
            ['id' => 40, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Truyền thông tin bằng sóng vô tuyến', 'grade' => 11, 'order' => 2, 'total_periods' => 10, 'created_at' => '2026-03-25 04:41:03', 'updated_at' => '2026-03-25 04:48:40'],
            ['id' => 41, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Mở đầu về điện tử học', 'grade' => 11, 'order' => 3, 'total_periods' => 10, 'created_at' => '2026-03-25 04:41:22', 'updated_at' => '2026-03-25 04:48:47'],
            ['id' => 42, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Dao động và Sóng', 'grade' => 11, 'order' => 1, 'total_periods' => 30, 'created_at' => '2026-03-25 04:41:39', 'updated_at' => '2026-03-27 00:52:51'],
            ['id' => 43, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Quang học', 'grade' => 11, 'order' => 2, 'total_periods' => 20, 'created_at' => '2026-03-25 04:41:51', 'updated_at' => '2026-03-27 00:52:59'],
            ['id' => 44, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Trường điện từ', 'grade' => 11, 'order' => 3, 'total_periods' => 20, 'created_at' => '2026-03-25 04:42:07', 'updated_at' => '2026-03-27 00:53:05'],
            ['id' => 45, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Dòng điện. Linh kiện điện tử', 'grade' => 11, 'order' => 4, 'total_periods' => 15, 'created_at' => '2026-03-25 04:42:25', 'updated_at' => '2026-03-27 00:53:11'],
            ['id' => 46, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Thuyết tương đối hẹp', 'grade' => 11, 'order' => 5, 'total_periods' => 15, 'created_at' => '2026-03-25 04:42:38', 'updated_at' => '2026-03-27 00:53:18'],
            ['id' => 47, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Thực hành', 'grade' => 11, 'order' => 6, 'total_periods' => 15, 'created_at' => '2026-03-25 04:42:52', 'updated_at' => '2026-03-27 00:53:23'],
            ['id' => 48, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Vật lí nhiệt', 'grade' => 12, 'order' => 1, 'total_periods' => 14, 'created_at' => '2026-03-25 04:43:18', 'updated_at' => '2026-03-25 04:49:09'],
            ['id' => 49, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Khí lí tưởng', 'grade' => 12, 'order' => 2, 'total_periods' => 12, 'created_at' => '2026-03-25 04:43:30', 'updated_at' => '2026-03-25 04:49:15'],
            ['id' => 50, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Từ trường', 'grade' => 12, 'order' => 3, 'total_periods' => 18, 'created_at' => '2026-03-25 04:43:44', 'updated_at' => '2026-03-25 04:49:21'],
            ['id' => 51, 'subject_id' => 1, 'topic_type_id' => 4, 'name' => 'Vật lí hạt nhân', 'grade' => 12, 'order' => 4, 'total_periods' => 16, 'created_at' => '2026-03-25 04:44:03', 'updated_at' => '2026-03-25 04:49:28'],
            ['id' => 60, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Dòng điện xoay chiều', 'grade' => 12, 'order' => 1, 'total_periods' => 10, 'created_at' => '2026-03-25 04:44:17', 'updated_at' => '2026-03-25 04:49:45'],
            ['id' => 61, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Một số ứng dụng vật lí trong chẩn đoán hình ảnh', 'grade' => 12, 'order' => 2, 'total_periods' => 10, 'created_at' => '2026-03-25 04:44:32', 'updated_at' => '2026-03-25 04:49:54'],
            ['id' => 62, 'subject_id' => 1, 'topic_type_id' => 5, 'name' => 'Vật lí lượng tử', 'grade' => 12, 'order' => 3, 'total_periods' => 15, 'created_at' => '2026-03-25 04:44:46', 'updated_at' => '2026-03-25 04:50:00'],
            ['id' => 63, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Vật lí nhiệt', 'grade' => 12, 'order' => 1, 'total_periods' => 15, 'created_at' => '2026-03-25 04:45:04', 'updated_at' => '2026-03-27 00:53:36'],
            ['id' => 64, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Vật lí lượng tử', 'grade' => 12, 'order' => 2, 'total_periods' => 15, 'created_at' => '2026-03-25 04:45:21', 'updated_at' => '2026-03-27 00:53:42'],
            ['id' => 65, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Vật lí hạt nhân', 'grade' => 12, 'order' => 3, 'total_periods' => 15, 'created_at' => '2026-03-25 04:45:34', 'updated_at' => '2026-03-27 00:53:48'],
            ['id' => 66, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Các hạt sơ cấp. Nhập môn thiên văn học', 'grade' => 12, 'order' => 4, 'total_periods' => 20, 'created_at' => '2026-03-25 04:45:51', 'updated_at' => '2026-03-27 00:53:53'],
            ['id' => 67, 'subject_id' => 1, 'topic_type_id' => 6, 'name' => 'Thực hành', 'grade' => 12, 'order' => 5, 'total_periods' => 15, 'created_at' => '2026-03-25 04:46:04', 'updated_at' => '2026-03-27 00:54:01'],
        ]);

        // 4. Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();
    }
}