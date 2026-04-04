<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionSetupRequest;
use App\Models\CognitiveLevel;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Topic;
use App\QuestionHandlers\EssayHandler;
use App\QuestionHandlers\MultipleChoiceHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class QuestionController extends Controller
{
    /**
     * Hiển thị danh sách câu hỏi với các bộ lọc và tìm kiếm
     */

    /* public function index(Request $request)
    {
        $topics = $this->getTopicsByRole();
        $treeByGrade = $topics->groupBy('grade');
        $hasNoAssignedTopics = $topics->isEmpty();

        // 1. Kiểm tra xem người dùng có đang gửi yêu cầu lọc mục tiêu HOẶC tìm tag_name không
        $isFiltering = $request->filled('filter_objective_ids') || $request->filled('tag_name');

        // 2. Mặc định danh sách câu hỏi là null
        $questions = null;

        // 3. Nếu có bấm lọc hoặc tìm kiếm, tiến hành lấy dữ liệu
        if ($isFiltering) {
            $query = Question::query();

            // Lọc qua bảng trung gian (Nếu có tích chọn Mục tiêu đánh giá)
            if ($request->filled('filter_objective_ids')) {
                $query->whereHas('objectives', function ($q) use ($request) {
                    $q->whereIn('objectives.id', $request->filter_objective_ids);
                });
            }

            // Lọc theo mã câu hỏi - tag_name (Nếu có nhập từ khóa tìm kiếm)
            if ($request->filled('tag_name')) {
                $query->where('tag_name', 'like', '%'.$request->tag_name.'%');
            }

            $questions = $query->paginate(15);
            $questions->appends($request->query());
        }

        // 4. Truyền thêm biến $isFiltering sang view
        return view('questions.index', compact('hasNoAssignedTopics', 'treeByGrade', 'questions', 'isFiltering'));
    } */

    /**
     * Hiển thị danh sách câu hỏi với các bộ lọc và tìm kiếm
     */
    public function index(Request $request)
    {
        $topics = $this->getTopicsByRole();
        $treeByGrade = $topics->groupBy('grade');
        $hasNoAssignedTopics = $topics->isEmpty();

        // 0. Lấy mảng ID của các Topic mà user hiện tại được phép truy cập
        $authorizedTopicIds = $topics->pluck('id')->toArray();

        // 1. Kiểm tra xem người dùng có đang gửi yêu cầu lọc mục tiêu HOẶC tìm tag_name không
        $isFiltering = $request->filled('filter_objective_ids') || $request->filled('tag_name');

        // 2. Mặc định danh sách câu hỏi là null
        $questions = null;

        // 3. Nếu có bấm lọc hoặc tìm kiếm, tiến hành lấy dữ liệu
        if ($isFiltering) {
            $query = Question::query();

            // =========================================================================
            // LÕI PHÂN QUYỀN (MỚI): LOẠI BỎ CÂU HỎI CÓ OBJECTIVE NẰM NGOÀI TOPIC CHO PHÉP
            // =========================================================================
            if ($hasNoAssignedTopics) {
                // Nếu User không được phân công Topic nào, ép truy vấn rỗng luôn
                $query->whereRaw('1 = 0');
            } else {
                // Sử dụng whereDoesntHave tương đương với NOT EXISTS trong SQL
                $query->whereDoesntHave('objectives', function ($q) use ($authorizedTopicIds) {

                    // Truy ngược từ Objective -> Content -> lấy topic_id để đối chiếu
                    // (Dựa trên cấu trúc 'contents.objectives' ở hàm getTopicsByRole)
                    $q->whereHas('content', function ($contentQuery) use ($authorizedTopicIds) {
                        $contentQuery->whereNotIn('topic_id', $authorizedTopicIds);
                    });

                    /** * LƯU Ý QUAN TRỌNG:
                     * Nếu bảng `objectives` của bạn có TRỰC TIẾP cột `topic_id`,
                     * hãy comment khối whereHas phía trên lại và sử dụng dòng ngắn gọn này:
                     * * $q->whereNotIn('topic_id', $authorizedTopicIds);
                     */
                });

                // (Tùy chọn) Chỉ lấy những câu hỏi đã được map ít nhất 1 objective (bỏ qua câu hỏi rác/lỗi)
                $query->has('objectives');
            }
            // =========================================================================

            // Lọc qua bảng trung gian (Nếu có tích chọn Mục tiêu đánh giá)
            if ($request->filled('filter_objective_ids')) {
                $query->whereHas('objectives', function ($q) use ($request) {
                    $q->whereIn('objectives.id', $request->filter_objective_ids);
                });
            }

            // Lọc theo mã câu hỏi - tag_name (Nếu có nhập từ khóa tìm kiếm)
            if ($request->filled('tag_name')) {
                $query->where('tag_name', 'like', '%'.$request->tag_name.'%');
            }

            $questions = $query->paginate(15);
            $questions->appends($request->query());
        }

        // 4. Truyền thêm biến $isFiltering sang view
        return view('questions.index', compact('hasNoAssignedTopics', 'treeByGrade', 'questions', 'isFiltering'));
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
    public function storeSetup(StoreQuestionSetupRequest $request)
    {
        // 1. Validate dữ liệu Bước 1
        $validated = $request->validated();

        // 2. LƯU VÀO SESSION thay vì Database (Lưu luôn cả objective_ids trong mảng này)
        session()->put('question_setup', $validated);

        // 3. Lấy mã code và ép về chữ thường để khớp với route (vd: 'es', 'mc')
        // 3. Lấy mã code (vd: 'ES', 'MC') và chuyển về chữ thường
        $questionType = QuestionType::find($validated['type']);
        $typeCode = strtolower($questionType->code);

        // 4. Chuyển hướng sang Bước 2 tương ứng
        return redirect()->route("questions.{$typeCode}.create");
    }

    public function show($id)
    {
        // 1. Lấy thông tin câu hỏi kèm theo loại câu hỏi
        $question = Question::with('questionType')->findOrFail($id);

        // 2. Kiểm tra nếu có Shared Context (Ngữ cảnh dùng chung)
        if (! empty($question->shared_context_id)) {
            /**
             * PHẦN ẨN: Xử lý chuyển hướng sang SharedContextController
             * return redirect()->route('shared_contexts.edit', $question->shared_context_id);
             */

            // Tạm thời báo lỗi hoặc xử lý khác nếu bạn chưa xây dựng SharedContextController
            return back()->with('error', 'Câu hỏi này thuộc một ngữ cảnh dùng chung đang được phát triển.');
        }

        // 2. Lấy mã loại câu hỏi (Ví dụ: MC, TF, SA, ES)
        $typeCode = strtoupper($question->questionType->code);

        // 3. Xác định Controller đích dựa trên typeCode
        $controllerClass = $this->getControllerByCode($typeCode);

        if (! $controllerClass) {
            return abort(404, "Không tìm thấy xử lý cho loại câu hỏi: {$typeCode}");
        }

        // 4. Gọi hàm show() của Controller đích và truyền $id
        // Sử dụng app()->make để Laravel tự động Inject các Dependency nếu có
        return app()->make($controllerClass)->show($id);
    }

    // Edit
    public function edit($id)
    {
        $question = Question::with([
            'questionType', 'cognitiveLevel', 'objectives', 'explanation',
        ])->findOrFail($id);

        if ($question->shared_context_id) {
            // Trỏ sang SharedContextController nếu có
            return redirect()->action([SharedContextController::class, 'edit'], ['id' => $question->shared_context_id]);
        }

        // --- ĐOẠN XỬ LÝ ẢNH CHẮC CHẮN ĂN ---
        // Thay thế trực tiếp '../storage/' thành '/storage/'
        if ($question->stem) {
            $question->stem = str_replace('../storage/', '/storage/', $question->stem);
        }

        if ($question->explanation && $question->explanation->content) {
            $question->explanation->content = str_replace('../storage/', '/storage/', $question->explanation->content);
        }

        // Lấy dữ liệu cho các Dropdown và Treeview
        $topics = $this->getTopicsByRole(); // Hàm có sẵn của bạn
        // Gọi lại hàm dùng chung để lấy cây chuyên đề chuẩn theo phân quyền
        $treeByGrade = $this->getTopicsByRole()->groupBy('grade');

        $cognitiveLevels = CognitiveLevel::all();

        // Lấy mảng ID các mục tiêu đã được chọn để tick xanh
        $selectedObjectiveIds = $question->objectives->pluck('id')->toArray();

        return view('questions.edit', compact('question', 'treeByGrade', 'cognitiveLevels', 'selectedObjectiveIds'));
    }

    /**
     * Cập nhật thiết lập cơ bản và điều hướng sang trang sửa chi tiết
     */

    /**
     * Cập nhật thiết lập cơ bản và điều hướng sang trang sửa chi tiết
     */
    public function update(Request $request, $question)
    {
        // 1. Kiểm tra (Validate) dữ liệu gửi lên từ blade
        $validated = $request->validate([
            'tag_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'cognitive_level_id' => 'required|exists:cognitive_levels,id',
            'objective_ids' => 'required|array|min:1',
            'objective_ids.*' => 'exists:objectives,id',
        ], [
            'objective_ids.required' => 'Vui lòng chọn ít nhất một mục tiêu đánh giá từ cây chuyên đề.',
        ]);

        // 2. Tìm câu hỏi và load kèm thông tin loại câu hỏi
        $questionModel = Question::with('questionType')->findOrFail($question);

        // 3. Cập nhật dữ liệu thiết lập cơ bản vào bảng questions
        $questionModel->update([
            'tag_name' => $validated['tag_name'],
            'name' => $validated['name'],
            'cognitive_level_id' => $validated['cognitive_level_id'],
        ]);

        // 4. Lưu danh sách mục tiêu đánh giá (Cập nhật bảng trung gian)
        // Hàm sync() tự động đối chiếu, thêm cái mới tích, xóa cái đã bỏ tích
        $questionModel->objectives()->sync($validated['objective_ids']);

        // ==========================================
        // 5. XÁC ĐỊNH LUỒNG ĐI TIẾP THEO
        // ==========================================

        // Trướng hợp 1: Có shared_context_id (Câu hỏi chùm)
        if ($questionModel->shared_context_id) {
            return redirect()->route('shared_contexts.edit', ['id' => $questionModel->shared_context_id])
                ->with('success', 'Đã lưu thiết lập. Vui lòng tiếp tục cập nhật nội dung dùng chung.');
        }

        // Trường hợp 2: Không có shared_context_id -> Dựa vào code của loại câu hỏi
        $questionTypeCode = strtolower($questionModel->questionType->code ?? '');

        // Danh sách Map mã câu hỏi sang tên Route tương ứng (dựa theo web.php của bạn)
        $routeMap = [
            'mc' => 'questions.mc.edit',
            'tf' => 'questions.tf.edit',
            'sa' => 'questions.sa.edit',
            'es' => 'questions.es.edit',
        ];

        // Nếu mã câu hỏi không có trong danh sách hỗ trợ thì báo lỗi lại
        if (! array_key_exists($questionTypeCode, $routeMap)) {
            return back()->with('error', 'Hệ thống chưa hỗ trợ chỉnh sửa chi tiết cho loại câu hỏi có mã: '.strtoupper($questionTypeCode));
        }

        // Điều hướng sang route edit của Controller tương ứng (VD: MultipleChoiceController@edit)
        return redirect()->route($routeMap[$questionTypeCode], ['id' => $questionModel->id])
            ->with('success', 'Đã lưu thiết lập. Vui lòng tiếp tục cập nhật nội dung chi tiết.');
    }

    // Xoá câu hỏi
    public function destroy($id)
    {
        // dd($id);
        try {
            // 1. Tìm câu hỏi theo ID, nếu không có sẽ quăng lỗi ModelNotFoundException
            $question = Question::findOrFail($id);
            // dd($question);
            // KIỂM TRA SHARED CONTEXT CHẶN XÓA
            if (! empty($question->shared_context_id)) {
                return redirect()->route('questions.index')
                    ->with('error', 'Câu hỏi này thuộc cụm dữ liệu dùng chung (Shared Context) nên không thể xóa trực tiếp ở đây. (Tính năng quản lý cụm sẽ được cập nhật sau).');
            }

            // 2. Lấy mã loại câu hỏi (Giả sử bạn liên kết qua bảng question_types)
            $typeCode = $question->questionType->code; // VD: 'ES', 'MC', 'TF'...
            // dd($typeCode);
            // 3. Tìm Handler tương ứng
            $handler = $this->getHandlerByCode($typeCode);
            // dd($handler);
            // 4. Ủy quyền cho Handler xử lý việc xóa (Truyền object vào cho Handler dễ làm việc)
            $handler->destroy($question);

            return redirect()->route('questions.index')
                ->with('success', 'Đã xóa câu hỏi thành công!');

        } catch (ModelNotFoundException $e) {
            // Xử lý riêng khi không tìm thấy ID
            return redirect()->route('questions.index')
                ->with('error', 'Không tìm thấy dữ liệu câu hỏi này để xóa!');
        } catch (\Exception $e) {
            // Bắt các lỗi khác (ví dụ lỗi DB, lỗi xóa file...)
            return redirect()->route('questions.index')
                ->with('error', 'Có lỗi xảy ra khi xóa câu hỏi: '.$e->getMessage());
        }
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

    /**
     * Helper mapping mã loại câu hỏi với Controller tương ứng
     */
    private function getControllerByCode($code)
    {
        $map = [
            'MC' => MultipleChoiceController::class,
            'TF' => TrueFalseController::class,
            'SA' => ShortAnswerController::class,
            'ES' => EssayController::class,
        ];

        return $map[$code] ?? null;
    }

    private function getHandlerByCode($code)
    {
        return match ($code) {
            'ES' => app(EssayHandler::class),
            'MC' => app(MultipleChoiceHandler::class),
            'TF' => app(TrueFalseHandler::class),
            'SA' => app(ShortAnswerHandler::class),
            default => throw new \Exception('Không tìm thấy bộ xử lý cho loại câu hỏi này.'),
        };
    }
}
