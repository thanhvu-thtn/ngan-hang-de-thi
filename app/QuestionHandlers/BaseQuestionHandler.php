<?php

namespace App\QuestionHandlers;

use App\Models\CognitiveLevel;
use App\Models\Objective;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


abstract class BaseQuestionHandler implements QuestionHandlerInterface
{
    // Bổ sung thuộc tính và constructor cho Base
    protected \App\Services\ImageService $imageService;
    protected \App\Services\ObjectivePermissionService $permissionService; // Thêm dòng này

    public function __construct(\App\Services\ImageService $imageService, \App\Services\ObjectivePermissionService $permissionService)
    {
        $this->imageService = $imageService;
        $this->permissionService = $permissionService; // Thêm dòng này
    }

    /**
     * Kiểm tra 3 cửa bảo mật trước khi xóa câu hỏi
     * Trả về string (thông báo lỗi) nếu bị chặn, trả về null nếu hợp lệ.
     */
    public function checkDeletePermission(Question $question, User $user): ?string
    {
        // CỬA 1: Đang dùng chung Shared Context
        if (!is_null($question->shared_context_id)) {
            return 'Câu hỏi này đang nằm trong một cụm câu hỏi dùng chung, không thể xóa riêng lẻ.';
        }

        // CỬA 2: Trạng thái Đã duyệt nhưng không có quyền Thẩm định
        if ($question->status == 1 && !$user->can('tham-dinh-cau-hoi')) {
            return 'Câu hỏi đã được thẩm định, bạn không có quyền xóa.';
        }

        // CỬA 3: Objective nằm ngoài Topic được phân công
        // Lấy mảng mã định danh (tag_name) của các objective thuộc câu hỏi này
        $objectiveCodes = $question->objectives->pluck('tag_name')->toArray();
        //dd($user);
        $permissionCheck = $this->permissionService->verifyObjectivePermissions($objectiveCodes, $user);

        if (!$permissionCheck['is_valid']) {
            return 'Bạn không có quyền xóa câu hỏi này do chứa mục tiêu đánh giá ngoài phạm vi phân công.';
        }

        // Qua hết 3 cửa an toàn
        return null; 
    }

    /**
     * Nhạc trưởng cho việc Validate khi Import file
     * Nó sẽ tự động check phần chung, sau đó nhường cho class con check phần riêng.
     */
    public function validateImportData(array $questionData): array
    {
        $isValid = true;
        $errors = [];
        $warnings = [];

        // ==========================================
        // 1. KIỂM TRA PHẦN CHUNG (5 QUY TẮC)
        // ==========================================

        // --- Quy tắc 1: Mã định danh (tag_name) ---
        $tagName = trim($questionData['tag_name'] ?? '');
        if (empty($tagName)) {
            // Chỉ cảnh báo, việc gán UUID sẽ làm ở bước Save
            $warnings[] = 'Bạn chưa nhập mã định danh (Hệ thống sẽ tự động cấp mã khi lưu).';
        } else {
            $isExist = Question::where('tag_name', $tagName)->exists();
            if ($isExist) {
                // Chỉ cảnh báo, việc cộng chuỗi UUID sẽ làm ở bước Save
                $warnings[] = "Mã định danh '{$tagName}' đã tồn tại trong hệ thống (Sẽ tự động thêm hậu tố để tránh trùng lặp).";
            }
        }

        // --- Quy tắc 2: Tóm tắt câu hỏi (name) ---
        if (empty(trim($questionData['name'] ?? ''))) {
            // Chỉ cảnh báo
            $warnings[] = 'Bạn chưa nhập tóm tắt câu hỏi này (Hệ thống sẽ dùng tên mặc định khi lưu).';
        }

        // --- Quy tắc 3: Mã kiểu câu hỏi (Type) ---
        $typeTag = trim(strip_tags($questionData['type'] ?? ''));
        if (empty($typeTag)) {
            $isValid = false;
            $errors[] = 'Bạn chưa nhập kiểu câu hỏi.';
        } else {
            $isTypeValid = QuestionType::where('code', $typeTag)->exists();
            if (! $isTypeValid) {
                $isValid = false;
                $errors[] = "Kiểu câu hỏi '{$typeTag}' không hợp lệ.";
            }
        }

        // --- Quy tắc 4: Mức độ nhận thức (Cognitive Level) ---
        $cognitiveTag = trim(strip_tags($questionData['cognitive_level_tag'] ?? ''));
        if (empty($cognitiveTag)) {
            $isValid = false;
            $errors[] = 'Câu hỏi thiếu mức độ nhận thức.';
        } else {
            $isLevelValid = CognitiveLevel::where('tag_name', $cognitiveTag)->exists();
            if (! $isLevelValid) {
                $isValid = false;
                $errors[] = "Mức độ nhận thức '{$cognitiveTag}' không hợp lệ.";
            }
        }

        // --- Quy tắc 5: Phần dẫn câu hỏi (Stem) ---
        // Vẫn giữ lại thẻ img và math để tránh việc câu hỏi chỉ có ảnh/công thức bị báo rỗng
        $stem = trim(strip_tags($questionData['stem'] ?? '', '<img><math>'));
        if (empty($stem)) {
            $isValid = false;
            $errors[] = 'Câu hỏi này không có phần dẫn.';
        }

        // ==========================================
        // 2. GỌI KIỂM TRA PHẦN RIÊNG TỪ CLASS CON
        // ==========================================
        $specificValidation = $this->validateSpecificImportData($questionData);

        // ==========================================
        // 3. TỔNG HỢP KẾT QUẢ VÀ TRẢ VỀ
        // ==========================================
        return [
            'is_valid' => $isValid && $specificValidation['is_valid'],
            'errors' => array_merge($errors, $specificValidation['errors']),
            'warnings' => array_merge($warnings, $specificValidation['warnings']),
        ];
    }

    /**
     * Hàm trừu tượng (Bắt buộc các class con phải định nghĩa để tự check phần riêng của mình)
     */
    abstract protected function validateSpecificImportData(array $questionData): array;

    /**
     * Hàm dùng chung để lưu dữ liệu câu hỏi từ mảng Import
     */
    public function storeImportData(array $questionData, int $userId, ?int $sharedContextId = null): Question
    {
        return \DB::transaction(function () use ($questionData, $sharedContextId) {

            // 1. Lấy ID của QuestionType và CognitiveLevel
            $typeId = QuestionType::where('code', $questionData['type'])->value('id');
            $levelId = CognitiveLevel::where('tag_name', $questionData['cognitive_level_tag'])->value('id');

            // --- XỬ LÝ DỮ LIỆU THIẾU (DEFAULT VALUES) ---
            // Nếu tag_name rỗng, tự động sinh ra một mã ngẫu nhiên (UUID) để tránh lỗi trùng lặp
            // --- XỬ LÝ MÃ ĐỊNH DANH (TAG_NAME) ĐỂ KHÔNG BỊ TRÙNG ---
            $tagName = trim($questionData['tag_name'] ?? '');

            if (empty($tagName)) {
                // Trường hợp 1: Không nhập gì -> Sinh nguyên 1 cái UUID
                $tagName = Str::uuid()->toString();
            } else {
                // Trường hợp 2: Có nhập -> Kiểm tra xem đã tồn tại chưa
                if (Question::where('tag_name', $tagName)->exists()) {
                    // Nếu trùng, cộng thẳng luôn 1 cái UUID vào đuôi (VD: BAI1-C1-123e4567-e89b-12d3-a456-426614174000)
                    $tagName = $tagName.'-'.Str::uuid()->toString();
                }
            }

            // Nếu name rỗng, gán chuỗi mặc định
            $name = ! empty($questionData['name'])
                        ? $questionData['name']
                        : 'Câu hỏi chưa có tóm tắt';

            // 2. Tạo bản ghi Question
            $question = Question::create([
                'tag_name' => $tagName,
                'name' => $name,
                'question_type_id' => $typeId,
                'cognitive_level_id' => $levelId,
                'shared_context_id' => $sharedContextId,
                'stem' => $questionData['stem'],
                'status' => 0, // Mặc định chờ duyệt
                // 'layout_id'       => có thể bổ sung nếu mảng import có quy định layout
            ]);

            // 3. Lưu Lời giải (Explanation) nếu có
            if (! empty($questionData['explanation'])) {
                $question->explanation()->create([
                    'content' => $questionData['explanation'],
                ]);
            }

            // 4. Lưu quan hệ Mục tiêu (Objectives) nếu có
            if (! empty($questionData['objectives'])) {
                // Truy vấn DB để lấy mảng ID từ mảng tag_name
                $objectiveIds = Objective::whereIn('tag_name', $questionData['objectives'])
                    ->pluck('id')
                    ->toArray();

                // Nếu tìm thấy ít nhất 1 ID hợp lệ thì mới tiến hành sync
                if (! empty($objectiveIds)) {
                    $question->objectives()->sync($objectiveIds);
                }
            }

            // 5. GỌI HOOK: Để các Handler con tự lưu phần đặc thù (Choices)
            $this->storeSpecificImportData($question, $questionData);

            return $question;
        });
    }

    /**
     * Hàm trừu tượng để Handler con xử lý lưu Choices/Answers
     */
    abstract protected function storeSpecificImportData(Question $question, array $questionData): void;

    /**
     * Lấy toàn bộ dữ liệu của câu hỏi để hiển thị (Show / Preview)
     */
    public function getDetails(Question $question): array
    {
        // 1. Load các quan hệ chung để tránh lỗi N+1 query
        $question->loadMissing([
            'questionType',
            'cognitiveLevel',
            'objectives',
            'explanation',
            'sharedContext',
            'checker',
            'layout',
        ]);

        // 2. Đóng gói dữ liệu CHUNG
        $commonData = [
            'id' => $question->id,
            'tag_name' => $question->tag_name,
            'name' => $question->name,
            'stem' => $question->stem,
            'difficulty_index' => $question->difficulty_index,
            'status' => $question->status,
            'created_at' => $question->created_at->format('d/m/Y H:i'),
            'shared_context_id' => $question->shared_context_id ?? null,

            // CÁC TRƯỜNG HIỂN THỊ (Dùng cho Show)
            'type_name' => $question->questionType->name ?? 'Không xác định',
            'type_code' => $question->questionType->code ?? '',
            'cognitive_level' => $question->cognitiveLevel->name ?? 'Không xác định',
            'explanation' => $question->explanation->content ?? null,
            'shared_context' => $question->sharedContext->content ?? null,
            'layout_name' => $question->layout->name ?? null,
            'checker_name' => $question->checker->name ?? null,

            // CÁC TRƯỜNG ID GỐC (Bổ sung thêm để dùng cho Edit)
            'cognitive_level_id' => $question->cognitive_level_id,
            'type_id' => $question->question_type_id,
            'objective_ids' => $question->objectives->pluck('id')->toArray(), // Lấy mảng ID chuẩn đầu ra

            'objectives' => $question->objectives->map(function ($obj) {
                return [
                    'id' => $obj->id, // Bổ sung id
                    'tag_name' => $obj->tag_name,
                    'description' => $obj->description,
                ];
            })->toArray(),
        ];

        // 3. GỌI HOOK: Lấy dữ liệu RIÊNG từ class con
        $specificData = $this->getSpecificDetails($question);

        // 4. Gộp chung lại và trả về
        return array_merge($commonData, $specificData);
    }

    /**
     * Hàm trừu tượng để Handler con trả về dữ liệu đặc thù
     */
    abstract protected function getSpecificDetails(Question $question): array;

    // =========================================================================
    // CODE XỬ LÝ UPDATE TỪ GIAO DIỆN WEB (Template Method Pattern)
    // =========================================================================

    /**
     * Nhạc trưởng cho việc Validate khi User bấm nút "Cập nhật câu hỏi" trên Web.
     */
    public function validateUpdateRequest(Request $request, Question $question): array
    {
        // 1. CÁC QUY TẮC CHUNG CHO MỌI LOẠI CÂU HỎI
        $commonRules = [
            // tag_name: Không bắt buộc, nhưng nếu nhập thì phải duy nhất (ngoại trừ chính câu hỏi hiện tại đang sửa)
            'tag_name'           => 'required|string|max:255|unique:questions,tag_name,' . $question->id,
            'name' => 'nullable|string|max:255',
            'cognitive_level_id' => 'required|exists:cognitive_levels,id',
            'stem' => 'required|string',
            'explanation' => 'nullable|string',

            // Validate mảng Chuẩn đầu ra (nếu có tick chọn)
            'objective_ids' => 'nullable|array',
            'objective_ids.*' => 'exists:objectives,id',
            // BỔ SUNG 2 LUẬT NÀY ĐỂ DỮ LIỆU KHÔNG BỊ LARAVEL VỨT ĐI:
            'status'             => 'nullable|numeric',
            'difficulty_index'   => 'nullable|numeric|min:0|max:1',
        ];

        // 2. THÔNG BÁO LỖI TIẾNG VIỆT CHO PHẦN CHUNG
        $commonMessages = [
            'tag_name.required' => 'Mã định danh câu hỏi không được để trống.', // Thêm dòng này
            'tag_name.unique' => 'Mã định danh này đã tồn tại, vui lòng chọn mã khác hoặc để trống.',
            'cognitive_level_id.required' => 'Vui lòng chọn Mức độ nhận thức.',
            'stem.required' => 'Nội dung câu hỏi (đề bài) không được để trống.',
        ];

        // 3. GỌI HOOK LẤY LUẬT VÀ THÔNG BÁO TỪ CLASS CON (MultipleChoice, ShortAnswer,...)
        $specificRules = $this->getSpecificUpdateRules($request);
        $specificMessages = $this->getSpecificUpdateMessages();

        // 4. GỘP CHUNG VÀ CHẠY VALIDATE CỦA LARAVEL
        $rules = array_merge($commonRules, $specificRules);
        $messages = array_merge($commonMessages, $specificMessages);

        // Hàm này sẽ tự động redirect lùi lại kèm báo lỗi màu đỏ (errors) nếu validate xịt
        // Nếu qua ải, nó trả về mảng dữ liệu đã được lọc sạch sẽ
        return $request->validate($rules, $messages);
    }

    /**
     * Khai báo 2 hàm Trừu tượng (Abstract) bắt buộc các Handler con phải khai báo.
     * Nếu không có luật riêng thì con chỉ cần return mảng rỗng [].
     */
    abstract protected function getSpecificUpdateRules(Request $request): array;

    abstract protected function getSpecificUpdateMessages(): array;

    public function updateQuestionData(Question $question, array $validatedData): void
    {
        DB::transaction(function () use ($question, $validatedData) {
            
            // 1. XỬ LÝ ẢNH TRONG ĐỀ BÀI (STEM)
            $cleanStem = $this->imageService->localizeImages($validatedData['stem']);

            // 2. CHUẨN BỊ DỮ LIỆU CẬP NHẬT (Chỉ lấy các trường cơ bản)
            $updateData = [
                'tag_name'           => $validatedData['tag_name'] ?? $question->tag_name,
                'name'               => $validatedData['name'] ?? null,
                'stem'               => $cleanStem,
                'cognitive_level_id' => $validatedData['cognitive_level_id'],
                'layout_id'          => $validatedData['layout_id'] ?? $question->layout_id,
            ];

            // === KIỂM TRA QUYỀN THẨM ĐỊNH ===
            if (auth()->check() && auth()->user()->can('tham-dinh-cau-hoi')) {
                // Có quyền thì mới lấy dữ liệu từ form (nếu không có trên form thì giữ nguyên)
                $updateData['difficulty_index'] = $validatedData['difficulty_index'] ?? $question->difficulty_index;
                $updateData['status']           = $validatedData['status'] ?? $question->status;
                
                // Mở rộng: Có thể lưu luôn người duyệt và thời gian nếu status thay đổi thành Đã duyệt
                if (isset($validatedData['status']) && $validatedData['status'] == 1) {
                    $updateData['checker_id'] = auth()->id();
                    $updateData['checked_at'] = now();
                }
            }

            // Tiến hành cập nhật vào bảng questions
            $question->update($updateData);

            // 3. CẬP NHẬT LỜI GIẢI (Bảng riêng: question_explanations)
            if (isset($validatedData['explanation'])) {
                $cleanExplanation = $this->imageService->localizeImages($validatedData['explanation']);
                
                $question->explanation()->updateOrCreate(
                    ['question_id' => $question->id],
                    ['content' => $cleanExplanation]
                );
            }

            // 4. CẬP NHẬT CHUẨN ĐẦU RA (Quan hệ N-N)
            if (isset($validatedData['objective_ids']) && is_array($validatedData['objective_ids'])) {
                $question->objectives()->sync($validatedData['objective_ids']);
            } else {
                $question->objectives()->detach();
            }

            // 5. GỌI HOOK LƯU RIÊNG (Choices,...)
            $this->updateSpecificData($question, $validatedData);
        });
    }
    /*
     * Khai báo hàm trừu tượng bắt buộc các class con phải implement để lưu dữ liệu riêng
     */
    abstract protected function updateSpecificData(Question $question, array $validatedData): void;

    /*
     * Khai báo hàm trừu tượng bắt buộc các class con phải implement để xóa dữ liệu riêng
     */
    public function deleteQuestionData(\App\Models\Question $question): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($question) {
            // 1. Dọn rác hình ảnh trên server (nếu có)
            $this->imageService->deleteImagesFromContent($question->stem);
            foreach($question->choices as $choice) {
                $this->imageService->deleteImagesFromContent($choice->content);
            }
            if ($question->explanation) {
                $this->imageService->deleteImagesFromContent($question->explanation->content);
            }

            // 2. Kích hoạt nút tự hủy, Database sẽ tự lo phần còn lại (Choices, Statistics, Explanations, Pivot...)
            $question->delete();
        });
    }
}
