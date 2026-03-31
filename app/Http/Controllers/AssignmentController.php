<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class AssignmentController extends Controller
{
    /**
     * Hiển thị danh sách giáo viên và các quyền hiện tại
     */
    public function index()
    {
        $user = auth()->user();

        // 1. Lấy danh sách tài khoản có vai trò "Giáo viên" VÀ cùng môn học với Tổ trưởng
        $teachers = User::role('Giáo viên')
            ->where('subject_id', $user->subject_id)
            ->with('permissions')
            ->get();

        // 2. Định nghĩa danh sách MÃ QUYỀN (name) muốn GIẤU khỏi Tổ trưởng
        // Các quyền hệ thống của Admin thì khai báo vào đây
        $hiddenPermissions = [
            'quan-ly-user',
            'quan-ly-vai-tro',
            'quan-ly-quyen',
            // Thêm các quyền nhạy cảm khác nếu có...
        ];

        // 3. Truy vấn lấy quyền TỪ DATABASE, nhưng LỌC BỎ các quyền trong mảng $hiddenPermissions
        $permissions = Permission::whereNotIn('name', $hiddenPermissions)->get();

        return view('assignments.index', compact('teachers', 'permissions'));
    }

    /**
     * Xử lý lưu các quyền được check từ form
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        $teacherIds = $request->input('teacher_ids', []);
        $permissionsData = $request->input('permissions', []);

        foreach ($teacherIds as $teacherId) {
            $teacher = User::where('id', $teacherId)
                ->where('subject_id', $user->subject_id)
                ->first();

            if ($teacher) {
                $permsToAssign = $permissionsData[$teacherId] ?? []; 
                $teacher->syncPermissions($permsToAssign);
            }
        }

        return redirect()->route('assignments.index')->with('success', 'Đã cập nhật phân quyền thành công!');
    }
}