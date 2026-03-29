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
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // 1. TẠO CÁC QUYỀN (PERMISSIONS) CỐT LÕI
        // ==========================================
        $permissions = [
            'truy-cap-toan-he-thong',
            'truy-cap-bo-mon',
            'them-moi-cau-hoi', // Tương ứng quyền của người Biên soạn
            'sua-cau-hoi',      // Tương ứng quyền của người Biên tập
            'them-moi-de-thi',  // Tương ứng quyền của người Ra đề
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==========================================
        // 2. TẠO CÁC VAI TRÒ (ROLES) & GÁN QUYỀN
        // ==========================================
        
        // Vai trò Admin: Full quyền
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(Permission::all());

        // Vai trò Tổ trưởng: Full quyền trong bộ môn
        $roleToTruong = Role::firstOrCreate(['name' => 'Tổ trưởng']);
        $roleToTruong->givePermissionTo([
            'truy-cap-bo-mon',
            'them-moi-cau-hoi',
            'sua-cau-hoi',
            'them-moi-de-thi'
        ]);

        // Vai trò Biên soạn
        $roleBienSoan = Role::firstOrCreate(['name' => 'Biên soạn']);
        $roleBienSoan->givePermissionTo('them-moi-cau-hoi');

        // Vai trò Biên tập
        $roleBienTap = Role::firstOrCreate(['name' => 'Biên tập']);
        $roleBienTap->givePermissionTo('sua-cau-hoi');

        // Vai trò Ra đề
        $roleRaDe = Role::firstOrCreate(['name' => 'Ra đề']);
        $roleRaDe->givePermissionTo('them-moi-de-thi');

        // ==========================================
        // 3. TẠO TÀI KHOẢN ADMIN MẶC ĐỊNH
        // ==========================================
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Quản trị viên Hệ thống',
                'password' => Hash::make('12345678'),
            ]
        );
        // Gán Role Admin cho user này
        $adminUser->assignRole($roleAdmin);
    }
}