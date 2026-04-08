<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Hiển thị bảng ma trận Vai trò - Quyền hạn
     */
    public function index()
    {
        // Lấy tất cả Vai trò và Quyền lên để hiển thị giao diện
        $roles = Role::all();
        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Xử lý lưu khi Admin bấm Submit
     */
    public function update(Request $request)
    {
        // Nhận mảng dữ liệu từ form (cấu trúc mong muốn: [role_id => ['quyen-1', 'quyen-2']])
        $rolePermissions = $request->input('permissions', []); 

        $roles = Role::all();

        foreach ($roles as $role) {
            // Lấy danh sách các quyền được tick cho Role này (nếu không tick gì thì là mảng rỗng)
            $permsToAssign = $rolePermissions[$role->id] ?? [];
            
            // Đồng bộ quyền vào database (tự động thêm/xóa ở bảng role_has_permissions)
            $role->syncPermissions($permsToAssign);
        }

        // Xóa Cache của Spatie bằng code (khỏi phải chạy php artisan thủ công nữa!)
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return back()->with('success', 'Đã cập nhật phân quyền cho các Vai trò thành công!');
    }
}