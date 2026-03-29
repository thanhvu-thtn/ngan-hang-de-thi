<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Xóa cache của Spatie để tránh lỗi
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // 1. TẠO CÁC QUYỀN (PERMISSIONS) CỐT LÕI
        // ==========================================
        $permissions = [
            'truy-cap-toan-he-thong',
            'truy-cap-bo-mon',
            'them-moi-cau-hoi', 
            'sua-cau-hoi',      
            'them-moi-de-thi',  
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==========================================
        // 2. TẠO CÁC VAI TRÒ (ROLES) CHÍNH
        // ==========================================
        
        // Vai trò Admin: Mặc định có toàn quyền
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // Vai trò Tổ trưởng: Có toàn quyền trong phạm vi bộ môn
        $roleToTruong = Role::firstOrCreate(['name' => 'Tổ trưởng']);
        $roleToTruong->givePermissionTo([
            'truy-cap-bo-mon',
            'them-moi-cau-hoi',
            'sua-cau-hoi',
            'them-moi-de-thi'
        ]);

        // Vai trò Giáo viên: Mặc định TRẮNG QUYỀN (hoặc chỉ có quyền xem cơ bản). 
        // Các quyền thêm/sửa/ra đề sẽ được Tổ trưởng cấp TRỰC TIẾP cho User sau.
        $roleGiaoVien = Role::firstOrCreate(['name' => 'Giáo viên']);

        // ==========================================
        // 3. TẠO TÀI KHOẢN ADMIN MẶC ĐỊNH
        // ==========================================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản trị viên Hệ thống',
                'password' => '12345678',
            ]
        );
        $adminUser->assignRole($roleAdmin);
    }
}