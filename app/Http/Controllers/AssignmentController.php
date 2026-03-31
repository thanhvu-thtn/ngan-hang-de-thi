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
        $hiddenPermissions = [
            'quan-ly-user',
            'quan-ly-vai-tro',
            'quan-ly-quyen',
            // Thêm các quyền nhạy cảm khác nếu có...
        ];

        // 3. Truy vấn lấy quyền TỪ DATABASE, lọc bỏ các quyền hệ thống
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

        // [TỐI ƯU CODE THỪA]: Thay vì query tìm User từng lần trong vòng lặp (gây lỗi N+1 Query)
        // Ta gom lại lấy 1 lần duy nhất danh sách các giáo viên hợp lệ
        $teachers = User::whereIn('id', $teacherIds)
            ->where('subject_id', $user->subject_id)
            ->get();

        foreach ($teachers as $teacher) {
            $permsToAssign = $permissionsData[$teacher->id] ?? []; 
            
            // 1. Cập nhật phân quyền mới bằng Spatie
            $teacher->syncPermissions($permsToAssign);

            // ==========================================
            // 2. LOGIC MỚI: DỌN RÁC DATABASE CHUYÊN ĐỀ
            // ==========================================
            // Nếu trong danh sách quyền mới KHÔNG CÓ quyền 'bien-soan-cau-hoi'
            if (!in_array('bien-soan-cau-hoi', $permsToAssign)) {
                // Xóa trắng toàn bộ các chuyên đề đã phân cho giáo viên này trong bảng topic_user
                $teacher->topics()->detach();
            }
        }

        return redirect()->route('assignments.index')->with('success', 'Đã cập nhật phân quyền và dọn dẹp dữ liệu thành công!');
    }
}