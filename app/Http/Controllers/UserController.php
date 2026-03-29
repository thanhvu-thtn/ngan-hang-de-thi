<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Load danh sách user kèm theo môn học và vai trò (roles)
        $users = User::with(['subject', 'roles'])->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $roles = Role::orderBy('id')->get();
        return view('users.create', compact('subjects', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|exists:roles,name',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        // 1. Tạo User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'subject_id' => $request->subject_id,
        ]);

        // 2. Gán Role (Vai trò) cho User
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Thêm người dùng thành công!');
    }

    public function edit(User $user)
    {
        $subjects = Subject::orderBy('name')->get();
        $roles = Role::orderBy('id')->get();
        
        // Lấy tên Role hiện tại của user (nếu có)
        $userRole = $user->roles->pluck('name')->first(); 

        return view('users.edit', compact('user', 'subjects', 'roles', 'userRole'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        // 1. Cập nhật thông tin cơ bản
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'subject_id' => $request->subject_id,
        ];

        // Nếu người dùng có nhập mật khẩu mới thì mới cập nhật mật khẩu
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // 2. Cập nhật lại Role
        // Hàm syncRoles sẽ tự động xóa role cũ và gán role mới
        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    public function destroy(User $user)
    {
        // Bảo vệ: Không cho phép xóa chính tài khoản đang đăng nhập hoặc Admin gốc
        if ($user->hasRole('Admin') && User::role('Admin')->count() == 1) {
            return redirect()->route('users.index')->with('error', 'Không thể xóa Quản trị viên duy nhất của hệ thống!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Xóa người dùng thành công!');
    }
}