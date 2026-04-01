<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Topic;
use Illuminate\Support\Facades\Auth;
use App\Models\TopicType;

class TopicAssignmentController extends Controller
{
    /**
     * Hiển thị giao diện phân công chuyên đề
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $teachers = User::permission('bien-soan-cau-hoi')
            ->where('subject_id', $user->subject_id)
            ->where('id', '!=', $user->id)
            ->with('topics')
            ->get();

        $topicsByGrade = Topic::where('subject_id', $user->subject_id)
            ->orderBy('grade')
            ->orderBy('order')
            ->get()
            ->groupBy('grade');

        // LẤY DANH SÁCH LOẠI CHUYÊN ĐỀ TỪ DATABASE
        $topicTypes = TopicType::all(); 

        // Truyền thêm $topicTypes vào compact
        return view('topic-assignments.index', compact('teachers', 'topicsByGrade', 'topicTypes'));
    }

    /**
     * Xử lý lưu dữ liệu phân công
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $teacherIds = $request->input('teacher_ids', []);
        $topicsData = $request->input('topics', []); // Mảng dạng: teacher_id => [topic_id_1, topic_id_2]

        // Lấy danh sách giáo viên hợp lệ (tránh query N+1)
        $teachers = User::whereIn('id', $teacherIds)
            ->where('subject_id', $user->subject_id)
            ->get();

        foreach ($teachers as $teacher) {
            // Lấy danh sách ID chuyên đề được tick cho giáo viên này
            $assignedTopics = $topicsData[$teacher->id] ?? []; 
            
            // Hàm sync() tự động thêm/xóa dữ liệu trong bảng topic_user cho khớp với mảng $assignedTopics
            $teacher->topics()->sync($assignedTopics);
        }

        return redirect()->route('topic-assignments.index')
                         ->with('success', 'Đã lưu phân công chuyên đề thành công!');
    }
}