<?php

namespace App\Http\Controllers;

use App\Models\CognitiveLevel;
// use App\Models\Question; // Sau này sẽ dùng
use App\Models\QuestionType;
use App\Models\Topic;
use App\Models\User;
use App\QuestionHandlers\EssayHandler;
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

    // Thêm mới
    public function create()
    {
        // 1. Lấy danh mục tham số cấu hình
        $cognitiveLevels = CognitiveLevel::orderBy('level_weight', 'asc')->get();
        $questionTypes = QuestionType::all();

        // 2. Lấy cây chuyên đề của User hiện tại (Giống hệt logic bên hàm index)
        // Lấy các topics mà user được phân công, kèm theo loại chuyên đề, nội dung và mục tiêu
        $topics = auth()->user()->topics()
            ->with(['topicType', 'contents.objectives'])
            ->orderBy('grade', 'desc')
            ->orderBy('order', 'asc')
            ->get();

        // Nhóm các chuyên đề lại theo khối (grade)
        $treeByGrade = $topics->groupBy('grade');

        // 3. Render ra giao diện
        return view('questions.create', compact('cognitiveLevels', 'questionTypes', 'treeByGrade'));
    }

    // Thêm mới
    public function store(Request $request)
    {
        // Validation sẽ được xử lý trong QuestionHandler
        // Chỉ cần gọi hàm validate của handler để nhận về dữ liệu đã được xác thực
        $handler = new EssayHandler;
        $validatedData = $handler->validate($request);

        // Sau khi có dữ liệu đã xác thực, gọi hàm store của handler để lưu vào database
        $question = $handler->store($validatedData);

        return redirect()->route('questions.index')->with('success', 'Thêm câu hỏi thành công. Vui lòng chờ duyệt.');
    }
}
