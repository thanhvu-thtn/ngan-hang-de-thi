<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\StoreQuestionSetupRequest;
use App\Models\CognitiveLevel;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Topic;
use App\Models\QuestionLayout;
use App\QuestionHandlers\EssayHandler;
use App\QuestionHandlers\MultipleChoiceHandler;
use App\QuestionHandlers\ShortAnswerHandler;
use App\QuestionHandlers\TrueFalseHandler;
use App\Services\ObjectivePermissionService;
use App\Services\QuestionImportService;
use App\Services\WordService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class QuestionController extends Controller
{
    protected $permissionService;

    public function __construct(ObjectivePermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Hiển thị danh sách câu hỏi với các bộ lọc và tìm kiếm
     */
    public function index(Request $request)
    {
        // 1. Gọi thẳng Service để lấy Tree (Truyền true để lấy Count)
        $treeByGrade = $this->permissionService->getAllowedObjectiveTree(auth()->user(), true);
        $hasNoAssignedTopics = $treeByGrade->isEmpty();

        // 0. Lấy mảng ID của các Topic mà user hiện tại được phép truy cập
        // SỬA TẠI ĐÂY: Dùng collapse() để gộp các mảng con (theo grade) lại, sau đó lấy ID
        $authorizedTopicIds = $treeByGrade->collapse()->pluck('id')->toArray();

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
                    $q->whereHas('content', function ($contentQuery) use ($authorizedTopicIds) {
                        $contentQuery->whereNotIn('topic_id', $authorizedTopicIds);
                    });
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

        // LƯU LẠI ĐƯỜNG DẪN HIỆN TẠI (BAO GỒM CẢ QUERY LỌC VÀ PHÂN TRANG) VÀO SESSION
        session()->put('question_index_url', request()->fullUrl());

        // 4. Truyền thêm biến $isFiltering sang view
        return view('questions.index', compact('hasNoAssignedTopics', 'treeByGrade', 'questions', 'isFiltering'));
    }

    /**
     * Bước 1: Giao diện thiết lập thông tin ban đầu
     */
    /* public function create()
    {
        // Truyền false để không cần đếm câu hỏi cho nhẹ
        $treeByGrade = $this->permissionService->getAllowedObjectiveTree(auth()->user(), false);
        $cognitiveLevels = CognitiveLevel::all();
        $questionTypes = QuestionType::all();

        return view('questions.create', compact('cognitiveLevels', 'questionTypes', 'treeByGrade'));
    } */

    /**
     * Hiển thị giao diện Form tạo câu hỏi CHÍNH
     */
    public function create()
    {
        $user = auth()->user();

        // 1. Lấy cây mục tiêu/chuyên đề mà giáo viên này được phép biên soạn
        // Sử dụng Service có sẵn để đảm bảo chỉ hiện những gì giáo viên được phân công
        $treeByGrade = $this->permissionService->getAllowedObjectiveTree($user, false);

        // 2. Lấy các dữ liệu cấu hình khác
        $cognitiveLevels = CognitiveLevel::all();
        $questionTypes = QuestionType::all();

        // Truyền $user sang để hiển thị tên môn học
        return view('questions.create', compact('treeByGrade', 'cognitiveLevels', 'questionTypes', 'user'));
    }

    /**
     * Bước 1 (Xử lý): Lưu nháp câu hỏi và Chuyển hướng sang Bước 2
     */
    /* public function storeSetup(StoreQuestionSetupRequest $request)
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
    } */

    /**
     * Hàm phục vụ AJAX: Trả về file HTML của giao diện con tương ứng
     */
    public function getPartial($type_code)
    {
        // Kiểm tra xem file view con có tồn tại không (VD: resources/views/questions/partials/type_ES.blade.php)
        $viewName = 'questions.partials.type_'.$type_code;

        if (view()->exists($viewName)) {
            return view($viewName)->render();
        }

        return response()->json(['error' => 'Không tìm thấy giao diện cho loại câu hỏi này.'], 404);
    }

    /**
     * Xử lý lưu CHÍNH THỨC (Gom cả phần chung và phần riêng vào DB Transaction)
     */
    public function store(StoreQuestionRequest $request)
    {
        $validatedData = $request->validated();

        DB::beginTransaction();

        try {
            $questionType = QuestionType::where('code', $validatedData['type_code'])->firstOrFail();

            // 1. Đóng gói phần CHUNG (Đã bỏ created_by)
            $commonData = [
                'tag_name' => $validatedData['tag_name'],
                'name' => $validatedData['name'],
                'subject_id' => auth()->user()->subject_id,
                'cognitive_level_id' => $validatedData['cognitive_level_id'],
                'question_type_id' => $questionType->id,
            ];

            // 2. Gọi Handler xử lý phần RIÊNG (bóc ảnh) và LƯU vào DB
            $handler = $this->getHandlerByCode($validatedData['type_code']);
            $question = $handler->storeQuestion($commonData, $validatedData);

            // 3. Gắn Mục tiêu kiến thức (Bảng trung gian)
            $question->objectives()->attach($validatedData['objective_ids']);

            DB::commit();

            // return redirect()->route('questions.index')->with('success', 'Đã tạo câu hỏi thành công!');
            return redirect()->route('questions.edit', $question->id)->with('success', 'Đã tạo câu hỏi thành công!');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Có lỗi xảy ra khi lưu: '.$e->getMessage())->withInput();
        }
    }

    // -----------------------------------------------------------------

    public function show($id)
    {
        // 1. Tìm câu hỏi trong Database
        $question = Question::findOrFail($id);

        // 2. Xác định loại câu hỏi để gọi đúng "chuyên gia" (Handler) xử lý
        $typeCode = $question->questionType->code;
        $handler = $this->getHandlerByCode($typeCode);
        // 3. Lấy toàn bộ "cỗ" đã được Handler dọn sẵn (dữ liệu chung + riêng)
        $data = $handler->getDetails($question);

        // 4. Bưng lên giao diện Blade
        return view('questions.show', compact('data'));
    }

    // Edit
    public function edit($id)
    {
        try {
            // 1. Tìm câu hỏi theo ID, load sẵn các quan hệ để tránh N+1
            $question = Question::with(['questionType', 'objectives', 'cognitiveLevel'])
                ->findOrFail($id);

            $user = auth()->user();

            // --- KIỂM TRA 1: Quyền sửa câu hỏi đã thẩm định ---
            // Trạng thái 'approved' (đã thẩm định) thì phải có quyền 'tham-dinh-cau-hoi' mới được sửa
            if ($question->status == 1 && ! $user->can('tham-dinh-cau-hoi')) {
                return back()
                    ->with('error', 'Câu hỏi này đã được thẩm định, bạn không có quyền sửa.');
            }

            // --- KIỂM TRA 2: Quyền theo phân công Chuyên đề (Topic) ---
            $objectiveCodes = $question->objectives->pluck('tag_name')->toArray();

            // Sử dụng Service kiểm tra xem User có quản lý các mã Chuẩn đầu ra này không
            $permissionCheck = $this->permissionService->verifyObjectivePermissions($objectiveCodes, $user);

            if (! $permissionCheck['is_valid']) {
                return redirect()->route('questions.index')
                    ->with('error', 'Câu hỏi không có quyền sửa (nằm ngoài chuyên đề được phân công).');
            }

            // --- LẤY DỮ LIỆU CHI TIẾT QUA HANDLER ---
            $typeCode = $question->questionType->code ?? null;
            if (! $typeCode) {
                return back()->with('error', 'Không xác định được loại câu hỏi.');
            }

            $handler = $this->getHandlerByCode($typeCode);
            $data = $handler->getDetails($question);

            // --- LẤY MASTER DATA CHO DROPDOWNS ---
            $cognitiveLevels = CognitiveLevel::all();
            $layouts = QuestionLayout::all();
            $treeByGrade = $this->permissionService->getAllowedObjectiveTree($user, false);
            // BỔ SUNG DÒNG NÀY: Trích xuất mảng ID chuẩn đầu ra từ $data để truyền ra Blade
            $selectedObjectiveIds = $data['objective_ids'] ?? [];

            // Trả về view với đầy đủ biến (nhớ thêm 'selectedObjectiveIds' vào compact)
            return view('questions.edit', compact(
                'question',
                'data',
                'cognitiveLevels',
                'layouts',
                'treeByGrade',
                'selectedObjectiveIds'
            ));

        } catch (ModelNotFoundException $e) {
            // Trường hợp ID không tồn tại trong database
            return redirect()->route('questions.index')
                ->with('error', 'Không tìm thấy câu hỏi yêu cầu.');
        }
    }

    /**
     * Cập nhật thiết lập cơ bản và điều hướng sang trang sửa chi tiết
     */

    /**
     * Cập nhật thiết lập cơ bản và điều hướng sang trang sửa chi tiết
     */
    /**
     * Cập nhật câu hỏi
     */
    public function update(Request $request, Question $question)
    {
        // 1. Lấy mã loại câu hỏi (MC, SA, TF, ES...)
        $typeCode = $question->questionType->code;

        // 2. Gọi Handler tương ứng
        $handler = $this->getHandlerByCode($typeCode);

        // 3. Chạy Validate (tự động gộp chung + riêng)
        // Nếu lỗi, Laravel tự động quay lại form và báo chữ đỏ. Nếu pass, trả về mảng data sạch.
        $validatedData = $handler->validateUpdateRequest($request, $question);

        // 4. Nhờ Handler tiến hành cập nhật vào Database
        // (Chúng ta sẽ xây dựng hàm này ngay sau đây)
        $handler->updateQuestionData($question, $validatedData);

        // 5. Thành công thì báo cáo và quay về
        return redirect()->back()->with('success', 'Cập nhật câu hỏi thành công!');
    }

    // Xoá câu hỏi
    /**
     * Hiển thị màn hình xác nhận trước khi xóa
     */
    public function delete($id)
    {
        // 1. Tự tìm câu hỏi trong Database bằng ID (nếu không có sẽ tự văng lỗi 404)
        $question = Question::findOrFail($id);

        // Kiểm tra xem câu hỏi có bị mất liên kết loại câu hỏi không
        if (! $question->questionType) {
            return back()->with('error', 'Câu hỏi này bị lỗi dữ liệu (không xác định được loại câu hỏi), không thể thực hiện thao tác.');
        }

        // Lấy Handler tương ứng với loại câu hỏi
        $handler = $this->getHandlerByCode($question->questionType->code);

        // 2. Kiểm tra quyền xóa (gọi xuống Handler)
        $errorMsg = $handler->checkDeletePermission($question, auth()->user());

        // Nếu có lỗi (bị chặn ở 1 trong 3 cửa) -> Bật ngược lại kèm thông báo
        if ($errorMsg) {
            return back()->with('error', $errorMsg);
        }

        // 3. Nếu qua ải, lấy chi tiết câu hỏi (load các quan hệ cần thiết để view hiển thị)
        $question->load(['objectives', 'choices', 'questionType', 'cognitiveLevel']);

        // 4. Trả về màn hình confirm
        //dd($question); // Tạm thời dừng ở đây để kiểm tra dữ liệu câu hỏi trước khi hi
        return view('questions.confirm_delete', compact('question'));
    }

    public function destroy(Request $request, $id)
    {
        try {
            // 1. Tìm câu hỏi
            $question = Question::findOrFail($id);

            // 2. Lấy bộ xử lý (Handler) tương ứng với loại câu hỏi
            $handler = $this->getHandlerByCode($question->questionType->code);

            // 3. Verify quyền lần cuối (Cửa bảo vệ an toàn nhỡ ai đó gửi Request Fake)
            $errorMsg = $handler->checkDeletePermission($question, auth()->user());
            
            if ($errorMsg) {
                // Nếu không có quyền, đá văng về trang index kèm thông báo lỗi
                return redirect()->route('questions.index')
                                 ->with('error', $errorMsg);
            }

            // 4. Gọi Handler dọn dẹp toàn bộ dữ liệu
            $handler->deleteQuestionData($question);

            // 5. Xóa thành công, quay về danh sách
            return redirect()->route('questions.index')
                             ->with('success', 'Đã xóa câu hỏi và các dữ liệu liên quan thành công!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('questions.index')->with('error', 'Không tìm thấy câu hỏi này trong hệ thống.');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi khi xóa câu hỏi ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('questions.index')->with('error', 'Có lỗi hệ thống xảy ra khi xóa câu hỏi.');
        }
    }

    // Hiển thị form upload file Word để tạo hàng loạt câu hỏi
    public function showUploadForm()
    {
        return view('questions.upload');
    }

    public function previewUpload(Request $request, WordService $wordService, QuestionImportService $importService)
    {
        $request->validate([
            'word_file' => 'required|file|mimes:docx|max:10240', // Tối đa 10MB
        ]);

        $file = $request->file('word_file');

        // 1. Lưu file Word người dùng tải lên vào thư mục tạm
        $tempDir = storage_path('app/temp_word');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $fileName = Str::random(10).'.docx';
        $file->move($tempDir, $fileName);
        $fullPath = $tempDir.'/'.$fileName;

        try {
            // 2. Gọi WordService dịch toàn bộ ra HTML
            $html = $wordService->convertWordToHtml($fullPath);

            // =========================================================
            // ĐOẠN CẦN CHỈNH SỬA: XỬ LÝ ĐƯỜNG DẪN ẢNH (DÙNG REGEX)
            // =========================================================

            // Xác định URL web cho thư mục chứa ảnh (Ví dụ: http://localhost:8000/storage/temp_media/media)
            $publicUrl = asset('storage/temp_media/media');

            // Dùng Regex tìm TẤT CẢ các thẻ src=".../storage/temp_media/media/tên_file"
            // và thay thế nó bằng URL web chuẩn.
            $html = preg_replace('/src="[^"]*?\/storage\/temp_media\/media\/([^"]+)"/i', 'src="'.$publicUrl.'/$1"', $html);

            // =========================================================
            // HẾT ĐOẠN CHỈNH SỬA
            // =========================================================
            // 3. Chạy hàm bóc tách HTML thành mảng các câu hỏi (Magic nằm ở đây)
            // $questions = $this->parseHtmlToQuestions($html);
            // Code mới
            // 1. Chuyển HTML thành dạng Mảng Phẳng (Dữ liệu thô)
            $jsonFlat = $wordService->htmlToJson($html);
            $flatArray = json_decode($jsonFlat, true);

            // Bắt lỗi nếu file Word không có table
            if (isset($flatArray['error'])) {
                return response()->json(['success' => false, 'message' => $flatArray['error']]);
            }

            // 2. Chuyển mảng phẳng thành Cấu trúc Câu hỏi
            $structuredData = $importService->buildStructuredData($flatArray);

            // Bạn có thể dd ra xem cấu trúc đã chuẩn chưa:

            // 4. Dọn rác file Word
            File::delete($fullPath);

            // 5. NHẠC TRƯỞNG RA TAY: Quét qua 2 cửa kiểm duyệt
            // Lấy User hiện tại đang đăng nhập truyền vào
            $structuredData = $importService->evaluateImportData($structuredData, auth()->user());
            // =========================================================
            // MỚI THÊM: TẠO UUID VÀ LƯU VÀO CACHE (REDIS)
            // =========================================================
            $importUuid = (string) Str::uuid();
            Cache::put('import_data_'.$importUuid, $structuredData, now()->addHours(2));

            // Truyền thêm $importUuid sang giao diện
            return view('questions.preview', compact('structuredData', 'importUuid'));

            // (Sau này ta sẽ return view('questions.preview') ở đây)

        } catch (\Exception $e) {
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }

            return back()->with('error', 'Lỗi xử lý file: '.$e->getMessage());
        }
    }

    public function importData(Request $request, QuestionImportService $importService)
    {
        $uuid = $request->input('import_uuid');

        $structuredData = \Cache::pull('import_data_'.$uuid);

        if (! $structuredData) {
            return redirect()->route('questions.index')->with('error', 'Dữ liệu không tồn tại hoặc đã hết hạn.');
        }

        try {
            // Gọi nhạc trưởng ra tay

            $totalSaved = $importService->saveStructuredData($structuredData, auth()->id());

            return redirect()->route('questions.index')
                ->with('success', "Đã import thành công {$totalSaved} câu hỏi vào ngân hàng!");

        } catch (\Exception $e) {
            \Log::error('Import Error: '.$e->getMessage());

            return back()->with('error', 'Có lỗi xảy ra trong quá trình lưu dữ liệu: '.$e->getMessage());
        }
    }

    // ==========================================
    // CÁC HÀM HELPER DÙNG CHUNG TRONG CLASS
    // ==========================================

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

    /**
     * API: Kiểm tra mã định danh (tag_name) có bị trùng không (Dùng cho AJAX)
     */
    public function checkTagName(Request $request)
    {
        $exists = Question::where('tag_name', $request->tag_name)->exists();

        return response()->json(['exists' => $exists]);
    }
}
