<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddNewPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Xóa cache của Spatie để hệ thống nhận diện quyền mới ngay lập tức
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Danh sách các quyền chi tiết (bạn có thể thêm bớt tùy ý sau này)
        $newPermissions = [
            'bien-soan-cau-hoi', // Thêm câu hỏi mới (Add new)
            'bien-tap-cau-hoi',  // Chỉnh sửa câu hỏi (Edit)
            'xoa-cau-hoi',       // Xóa câu hỏi
            'tao-de-thi',        // Tạo và xuất đề thi
            'cham-bai',          // Chấm bài thi (quyền mở rộng)
        ];

        // 2. Tạo quyền vào database 
        // Dùng firstOrCreate để nếu quyền đã tồn tại thì nó bỏ qua, KHÔNG BỊ LỖI
        foreach ($newPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. Cập nhật quyền cho Admin (Admin mặc định ôm trọn tất cả các quyền)
        $roleAdmin = Role::where('name', 'Admin')->first();
        if ($roleAdmin) {
            $roleAdmin->givePermissionTo(Permission::all());
        }

        // 4. Cập nhật quyền cho Tổ trưởng 
        // (Tổ trưởng mặc định có các quyền này để làm việc, và họ sẽ lấy các quyền này đi "phát" cho Giáo viên)
        $roleToTruong = Role::where('name', 'Tổ trưởng')->first();
        if ($roleToTruong) {
            $roleToTruong->givePermissionTo([
                'bien-soan-cau-hoi',
                'bien-tap-cau-hoi',
                'tao-de-thi',
                'cham-bai'
            ]);
        }
        
        $this->command->info('Đã bổ sung các quyền mới thành công và giữ nguyên dữ liệu cũ!');
    }
}