<?php

namespace App\QuestionHandlers;

use App\Models\CognitiveLevel;
use App\Models\Objective;
use App\Models\Question;
use App\Models\QuestionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

abstract class BaseQuestionHandler implements QuestionHandlerInterface
{
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
            'type_id'            => $question->question_type_id,
            'objective_ids'      => $question->objectives->pluck('id')->toArray(), // Lấy mảng ID chuẩn đầu ra

            'objectives' => $question->objectives->map(function ($obj) {
                return [
                    'id'          => $obj->id, // Bổ sung id
                    'tag_name'    => $obj->tag_name,
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
}
