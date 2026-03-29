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

        // Lấy danh sách tài khoản có vai trò "Giáo viên" VÀ cùng môn học với Tổ trưởng đang đăng nhập
        // Lấy kèm luôn bảng 'permissions' để tí nữa check xem họ đang có quyền gì
        $teachers = User::role('Giáo viên')
            ->where('subject_id', $user->subject_id)
            ->with('permissions')
            ->get();

        // Danh sách các quyền cốt lõi cần cấp phát hiển thị lên bảng
        $permissions = [
            'bien-soan-cau-hoi' => 'Biên soạn câu hỏi',
            'bien-tap-cau-hoi'  => 'Biên tập câu hỏi',
            'tao-de-thi'        => 'Ra đề thi'
        ];

        return view('assignments.index', compact('teachers', 'permissions'));
    }

    /**
     * Xử lý lưu các quyền được check từ form
     */
    public function update(Request $request)
    {
        $user = auth()->user();
        
        // Lấy danh sách ID giáo viên và mảng quyền từ form gửi lên
        $teacherIds = $request->input('teacher_ids', []);
        $permissionsData = $request->input('permissions', []);

        foreach ($teacherIds as $teacherId) {
            // Bảo mật lớp 2: Quét ID trong database nhưng phải ép điều kiện trùng môn học
            // Đề phòng hacker can thiệp F12 đổi ID của giáo viên môn khác
            $teacher = User::where('id', $teacherId)
                ->where('subject_id', $user->subject_id)
                ->first();

            if ($teacher) {
                // Lấy các quyền được check của giáo viên này (nếu không check ô nào thì mảng rỗng)
                $permsToAssign = $permissionsData[$teacherId] ?? []; 
                
                // Hàm syncPermissions của Spatie rất thông minh: 
                // Nó sẽ tự động xóa các quyền cũ đã bị bỏ tick và thêm các quyền mới được tick.
                $teacher->syncPermissions($permsToAssign);
            }
        }

        return redirect()->route('assignments.index')->with('success', 'Đã cập nhật phân quyền thành công!');
    }
}