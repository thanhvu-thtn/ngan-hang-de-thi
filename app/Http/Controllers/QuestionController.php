<?php

namespace App\Http\Controllers;

use App\Models\CognitiveLevel;
use App\Models\QuestionType;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Danh sách câu hỏi / Chọn chuyên đề
     */
    public function index()
    {
        $topics = $this->getTopicsByRole();
        $treeByGrade = $topics->groupBy('grade');
        $hasNoAssignedTopics = $topics->isEmpty();

        return view('questions.index', compact('treeByGrade', 'hasNoAssignedTopics'));
    }

    /**
     * Bước 1: Giao diện thiết lập thông tin ban đầu
     */
    public function create()
    {
        $cognitiveLevels = CognitiveLevel::orderBy('level_weight', 'asc')->get();
        $questionTypes = QuestionType::all();

        // Gọi lại hàm dùng chung để lấy cây chuyên đề chuẩn theo phân quyền
        $treeByGrade = $this->getTopicsByRole()->groupBy('grade');

        return view('questions.create', compact('cognitiveLevels', 'questionTypes', 'treeByGrade'));
    }

    /**
     * Bước 1 (Xử lý): Lưu nháp câu hỏi và Chuyển hướng sang Bước 2
     */
    public function storeSetup(Request $request)
    {
        // 1. Validate dữ liệu Bước 1
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tag_name' => 'required|string|unique:questions,tag_name',
            'type' => 'required|exists:question_types,id',
            'level' => 'required|exists:cognitive_levels,id',
            'objective_ids' => 'required|array',
            'objective_ids.*' => 'exists:objectives,id',
        ], [
            'tag_name.unique' => 'Mã / Từ khóa này đã tồn tại, vui lòng nhập mã khác.',
            'objective_ids.required' => 'Bạn phải chọn ít nhất 1 mục tiêu đánh giá.',
        ]);

        // 2. LƯU VÀO SESSION thay vì Database (Lưu luôn cả objective_ids trong mảng này)
        session()->put('question_setup', $validated);

        // 3. Lấy mã code và ép về chữ thường để khớp với route (vd: 'es', 'mc')
        $typeCode = strtolower(QuestionType::find($validated['type'])->code);

        // 4. Chuyển hướng sang Bước 2 tương ứng
        return redirect()->route("questions.{$typeCode}.create");
    }

    // ==========================================
    // CÁC HÀM HELPER DÙNG CHUNG TRONG CLASS
    // ==========================================

    /**
     * Lấy danh sách Topic dựa theo phân quyền User (Tránh lặp code)
     */
    private function getTopicsByRole()
    {
        $user = Auth::user();

        $query = Topic::with(['topicType', 'contents.objectives' => function ($q) {
            $q->withCount('questions');
        }]);

        if ($user->hasRole(['admin', 'Admin'])) {
            // Admin thấy toàn bộ
        } elseif ($user->hasRole('Tổ trưởng')) {
            // Tổ trưởng thấy theo môn
            $query->where('subject_id', $user->subject_id);
        } else {
            // Giáo viên thấy theo phân công
            $assignedTopicIds = $user->topics()->pluck('topics.id');
            if ($assignedTopicIds->isEmpty()) {
                return collect(); // Trả về mảng rỗng luôn cho lẹ
            }
            $query->whereIn('id', $assignedTopicIds);
        }

        return $query->orderBy('grade')->orderBy('order')->get();
    }
}
