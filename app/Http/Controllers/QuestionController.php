<?php

namespace App\Http\Controllers;

use App\Models\Topic;
// use App\Models\Question; // Sau này sẽ dùng
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        // In ra màn hình xem user này đang có những role gì
        // dd($user->getRoleNames());

        // Chuẩn bị Query lấy Topic kèm theo Content -> Objective -> Số lượng Question
        $query = Topic::with(['topicType', 'contents.objectives' => function ($q) {
            $q->withCount('questions'); // Tự động đếm số câu hỏi của từng objective
        }]);

        // ==========================================
        // LOGIC KIỂM TRA QUYỀN TRUY XUẤT CHUYÊN ĐỀ
        // ==========================================
        $hasNoAssignedTopics = false;

        // Xóa dòng dd($user->getRoleNames()); đi nhé

        if ($user->hasRole('admin') || $user->hasRole('Admin')) {
            // 1. Admin: Thấy TOÀN BỘ chuyên đề của toàn trường
            // Không cần where thêm gì cả

        } elseif ($user->hasRole('Tổ trưởng')) { // SỬA ĐÚNG CHỮ NÀY
            // 2. Tổ trưởng: Thấy TOÀN BỘ chuyên đề của MÔN HỌC mình quản lý
            $query->where('subject_id', $user->subject_id);

        } else {
            // 3. Giáo viên bình thường: Chỉ thấy các chuyên đề được phân công
            $assignedTopicIds = $user->topics()->pluck('topics.id');

            if ($assignedTopicIds->isEmpty()) {
                $hasNoAssignedTopics = true;
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $assignedTopicIds);
            }
        }

        // Thực thi query, sắp xếp theo khối và thứ tự, sau đó nhóm theo khối
        $topics = $query->orderBy('grade')
            ->orderBy('order')
            ->get();

        $treeByGrade = $topics->groupBy('grade');

        return view('questions.index', compact('treeByGrade', 'hasNoAssignedTopics'));
    }
}
