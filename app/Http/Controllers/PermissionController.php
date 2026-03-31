<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    // Xem danh sách và Form thêm mới
    public function index()
    {
        $permissions = Permission::orderBy('id', 'desc')->get();
        return view('permissions.index', compact('permissions'));
    }

    // Lưu quyền mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ], [
            'name.required' => 'Vui lòng nhập tên quyền.',
            'name.unique' => 'Tên quyền này đã tồn tại trong hệ thống.'
        ]);

        $name = Str::slug($request->name);

        Permission::create([
            'name' => $name,
            'guard_name' => 'web' 
        ]);

        return redirect()->route('permissions.index')->with('success', 'Thêm quyền mới thành công!');
    }

    // Giao diện sửa quyền
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    // Lưu cập nhật quyền
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => Str::slug($request->name),
            'guard_name' => 'web'
        ]);

        return redirect()->route('permissions.index')->with('success', 'Cập nhật quyền thành công!');
    }

    // Xóa quyền
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Đã xóa quyền thành công!');
    }
}