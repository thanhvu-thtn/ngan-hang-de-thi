<?php

namespace App\Services;

use App\QuestionHandlers\EssayHandler;
use App\QuestionHandlers\MultipleChoiceHandler;
use App\QuestionHandlers\ShortAnswerHandler;
use App\QuestionHandlers\TrueFalseHandler;

use App\Models\User;
use Exception;

class QuestionImportService
{
    protected $permissionService;

    public function __construct(ObjectivePermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }


    /**
     * Bước 1: Nhào nặn mảng phẳng thành cấu trúc mảng phân cấp (Structured Data)
     */
    /**
     * Bước 1: Nhào nặn mảng phẳng thành cấu trúc Model chuẩn (Structured Data)
     */
    public function buildStructuredData(array $flatData)
    {
        $structuredData = [];
        $currentContext = null;
        $currentQuestion = null;

        // Các biến lưu tạm (vì Choice và Answer có thể nằm lộn xộn thứ tự)
        $rawChoices = [];
        $rawAnswer = null;

        foreach ($flatData as $row) {
            $field = strtolower(trim($row['field']));
            $content = trim($row['content']);
            $rawContentText = strtolower(trim(strip_tags($content)));

            // 1. MỞ BLOCK (BEGIN)
            if (str_starts_with($field, 'begin')) {
                if (str_contains($rawContentText, 'sharedcontext')) {
                    // Cấu trúc chuẩn theo SharedContext Model
                    $currentContext = [
                        'type' => 'SharedContext',
                        'context_data' => [
                            'tag_name' => null,
                            'content' => null,
                            'note' => null,
                        ],
                        'questions' => [],
                    ];
                } else {
                    // Cấu trúc chuẩn theo Question Model
                    $currentQuestion = [
                        'tag_name' => null,
                        'name' => null,
                        'type' => null,           // Sẽ mapping sang question_type_id sau
                        'stem' => null,
                        'explanation' => null,
                        'objectives' => [],       // Mảng chứa chuẩn đầu ra (Text)
                        'choices' => [],          // Chuẩn bị hứng mảng Model QuestionChoice
                        // BỔ SUNG DÒNG NÀY:
                        'cognitive_level_tag' => null,
                    ];
                    $rawChoices = []; // Reset choice tạm
                    $rawAnswer = null; // Reset answer tạm
                }
            }
            // 2. ĐÓNG BLOCK (END)
            elseif (str_starts_with($field, 'end')) {
                if (str_contains($rawContentText, 'sharedcontext') && $currentContext !== null) {
                    $structuredData[] = $currentContext;
                    $currentContext = null;
                } elseif ($currentQuestion !== null) {

                    // --- BẮT ĐẦU XỬ LÝ ANSWER VÀ CHOICES TRƯỚC KHI ĐÓNG CÂU HỎI ---
                    $currentQuestion['choices'] = $this->parseChoicesAndAnswer($rawChoices, $rawAnswer);
                    // -------------------------------------------------------------

                    if ($currentContext !== null) {
                        $currentContext['questions'][] = $currentQuestion;
                    } else {
                        $structuredData[] = ['type' => 'IndependentQuestion', 'question_data' => $currentQuestion];
                    }
                    $currentQuestion = null;
                }
            }
            // 3. MAP DỮ LIỆU VÀO CÁC TRƯỜNG CHUẨN
            else {
                if ($field === 'anwer' || $field === 'answer') {
                    $rawAnswer = strip_tags($content); // Lưu tạm chuỗi Answer (VD: "AC" hoặc "1, 3")
                }

                if ($currentQuestion !== null) {
                    if ($field === 'objective' || $field === 'objectives') {
                        $objectives = explode('#', strip_tags($content));
                        $currentQuestion['objectives'] = array_filter(array_map('trim', $objectives));
                    } elseif (str_starts_with($field, 'choice')) {
                        $rawChoices[] = $content; // Gom tạm các choice vào mảng thô
                    }
                    // ---> BỔ SUNG ĐOẠN NÀY <---
                    elseif ($field === 'cognitivelevel') {
                        $currentQuestion['cognitive_level_tag'] = trim(strip_tags($content));
                    }
                    else {
                        // Khớp đúng tên field với cột trong database (Nếu Word ghi Tag -> tag_name)
                        $key = ($field === 'tag') ? 'tag_name' : $field;
                        if (array_key_exists($key, $currentQuestion)) {
                            $currentQuestion[$key] = $content;
                        }
                    }
                } elseif ($currentContext !== null) {
                    $key = ($field === 'tag') ? 'tag_name' : $field;
                    // Với Context, Word có thể ghi Stem hoặc Content, ta gom chung về cột content của Database
                    if ($key === 'stem') {
                        $key = 'content';
                    }

                    if (array_key_exists($key, $currentContext['context_data'])) {
                        $currentContext['context_data'][$key] = $content;
                    }
                }
            }
        }

        return $structuredData;
    }

    /**
     * Hàm phụ: Biến các lựa chọn thô và chuỗi đáp án thành mảng Model QuestionChoice
     */
    private function parseChoicesAndAnswer(array $rawChoices, ?string $rawAnswer)
    {
        $choices = [];
        $correctIndexes = [];

        // Nếu có Answer, ta lọc ra các ký tự CHỮ (A-Z) hoặc SỐ (1-9)
        // VD: "(ABCD)" -> "ABCD" | "1 3 4" -> "134" | "A,C" -> "AC"
        if ($rawAnswer) {
            $answerClean = strtoupper(preg_replace('/[^A-Z1-9]/i', '', $rawAnswer));

            foreach (str_split($answerClean) as $char) {
                if (is_numeric($char)) {
                    $correctIndexes[] = ((int) $char) - 1; // Số '1' -> Vị trí 0
                } else {
                    $correctIndexes[] = ord($char) - 65;  // Chữ 'A' (Mã ASCII 65) -> Vị trí 0, B -> 1
                }
            }
        }

        foreach ($rawChoices as $index => $choiceHtml) {
            // Xác định xem choice này có nằm trong danh sách đúng hay không
            $isCorrect = null;
            if ($rawAnswer !== null) {
                $isCorrect = in_array($index, $correctIndexes);
            }

            // Map y hệt cấu trúc của Model QuestionChoice
            $choices[] = [
                'content' => $choiceHtml,
                'is_correct' => $isCorrect, // true, false hoặc null
                'order' => $index + 1,
                'ratio' => 1.0, // Giá trị mặc định theo model
            ];
        }

        return $choices;
    }

    /**
     * Bước 2: Phân phối dữ liệu cho các Handler kiểm tra (Validate)
     * (Hàm này phục vụ cho tương lai khi bạn gọi lưu DB hoặc xuất Preview)
     */
    public function validateAndProcess(array $structuredData)
    {
        $results = [
            'valid' => [],
            'errors' => [],
        ];

        foreach ($structuredData as $index => $item) {
            try {
                if ($item['type'] === 'SharedContext') {
                    // TODO: Xử lý riêng cho SharedContext (Lặp qua $item['questions'])
                    // Tạm thời mình cứ ném vào mảng hợp lệ
                    $results['valid'][] = $item;
                } else {
                    $questionData = $item['question_data'];
                    $typeCode = strtoupper(strip_tags($questionData['type'] ?? ''));

                    $handler = $this->getHandlerByCode($typeCode);

                    if (! $handler) {
                        throw new Exception("Không tìm thấy bộ xử lý cho loại câu hỏi: {$typeCode}");
                    }

                    // Tưởng tượng trong EssayHandler của bạn có hàm validateImport($data)
                    // $validatedData = $handler->validateImport($questionData);

                    // Nếu validate qua môn, đưa vào mảng hợp lệ
                    $results['valid'][] = $item;
                }
            } catch (Exception $e) {
                // Nếu Handler ném ra lỗi, bắt lại để báo ra màn hình cho người dùng
                $results['errors'][] = [
                    'index' => $index,
                    'question_name' => strip_tags($item['question_data']['name'] ?? 'Không xác định'),
                    'message' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Nhạc trưởng điều phối kiểm tra dữ liệu trước khi Preview
     */
    public function evaluateImportData(array $structuredData, User $user): array
    {
        // Dùng tham chiếu (&$item) để sửa trực tiếp vào mảng gốc
        foreach ($structuredData as &$item) {
            
            if ($item['type'] === 'IndependentQuestion') {
                $objectives = $item['question_data']['objectives'] ?? [];

                // --- [CỬA 1]: Kiểm tra thẩm quyền (Yêu cầu cần đạt) ---
                $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

                // --- [CỬA 2]: Kiểm tra cấu trúc (Tạm khóa để test cửa 1) ---
                $door2 = ['is_valid' => true, 'errors' => []];

                // Gắn nhãn kết quả vào mảng dữ liệu
                $item['question_data']['is_ready_to_save']  = $door1['is_valid'] && $door2['is_valid'];
                $item['question_data']['permission_errors'] = $door1['errors'];
                $item['question_data']['format_errors']     = $door2['errors']; // Để dành chỗ cho cửa 2

            } elseif ($item['type'] === 'SharedContext') {
                // Với Shared Context, ta phải duyệt qua từng câu hỏi con
                $isContextReady = true;

                foreach ($item['questions'] as &$q) {
                    $objectives = $q['objectives'] ?? [];

                    // --- [CỬA 1] ---
                    $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

                    // --- [CỬA 2] (Tạm khóa) ---
                    $door2 = ['is_valid' => true, 'errors' => []];

                    // Gắn nhãn
                    $q['is_ready_to_save']  = $door1['is_valid'] && $door2['is_valid'];
                    $q['permission_errors'] = $door1['errors'];
                    $q['format_errors']     = $door2['errors'];

                    // Nếu 1 câu con hỏng, cả cụm Context sẽ không được lưu (tuỳ logic của bạn, ở đây tạm set là false)
                    if (!$q['is_ready_to_save']) {
                        $isContextReady = false;
                    }
                }

                // Gắn nhãn cho cả cụm Shared Context
                $item['is_ready_to_save'] = $isContextReady;
            }
        }

        return $structuredData;
    }


    /**
     * Helper tìm Handler dựa vào Mã (ES, MC, TF...)
     */
    private function getHandlerByCode($code)
    {
        return match (trim($code)) {
            'ES' => app(EssayHandler::class),
            'MC' => app(MultipleChoiceHandler::class),
            'TF' => app(TrueFalseHandler::class),
            'SA' => app(ShortAnswerHandler::class),
            default => null,
        };
    }
}
