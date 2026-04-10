<?php

namespace App\Services;

use App\Models\User;
use App\QuestionHandlers\EssayHandler;
use App\QuestionHandlers\MultipleChoiceHandler;
use App\QuestionHandlers\SharedContextHandler;
use App\QuestionHandlers\ShortAnswerHandler;
use App\QuestionHandlers\TrueFalseHandler;
use Exception;
use Illuminate\Support\Facades\DB;

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
                    } else {
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
    /*  public function evaluateImportData(array $structuredData, User $user): array
     {
         // Dùng tham chiếu (&$item) để sửa trực tiếp vào mảng gốc
         foreach ($structuredData as &$item) {

             if ($item['type'] === 'IndependentQuestion') {
                 $objectives = $item['question_data']['objectives'] ?? [];

                 // --- [CỬA 1]: Kiểm tra thẩm quyền (Yêu cầu cần đạt) ---
                 $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

                 // --- [CỬA 2]: Kiểm tra cấu trúc câu hỏi ---
                 $typeCode = strtoupper(strip_tags($item['question_data']['type'] ?? ''));
                 $handler = $this->getHandlerByCode($typeCode);

                 if ($handler) {
                     // Gọi hàm kiểm tra cấu trúc từ Handler tương ứng
                     $door2 = $handler->validateImportData($item['question_data']);
                 } else {
                     $door2 = [
                         'is_valid' => false,
                         'errors' => ["Không tìm thấy bộ xử lý cho loại câu hỏi: {$typeCode}"],
                     ];
                 }

                 // Gắn nhãn kết quả vào mảng dữ liệu
                 $item['question_data']['is_ready_to_save'] = $door1['is_valid'] && $door2['is_valid'];
                 $item['question_data']['permission_errors'] = $door1['errors'];
                 $item['question_data']['format_errors'] = $door2['errors'];

                 // SỬA LỖI 1: BỔ SUNG GÁN WARNING CHO CÂU HỎI ĐỘC LẬP TẠI ĐÂY
                 $item['question_data']['format_warnings'] = $door2['warnings'] ?? [];

             } elseif ($item['type'] === 'SharedContext') {
                 // Với Shared Context, ta phải duyệt qua từng câu hỏi con
                 $isContextReady = true;

                 foreach ($item['questions'] as &$q) {
                     $objectives = $q['objectives'] ?? [];

                     // --- [CỬA 1] ---
                     $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

                     // --- [CỬA 2]: Kiểm tra cấu trúc câu hỏi con ---
                     $typeCode = strtoupper(strip_tags($q['type'] ?? ''));
                     $handler = $this->getHandlerByCode($typeCode);

                     if ($handler) {
                         $door2 = $handler->validateImportData($q);
                     } else {
                         $door2 = [
                             'is_valid' => false,
                             'errors' => ["Không tìm thấy bộ xử lý cho loại câu hỏi: {$typeCode}"],
                         ];
                     }

                     // Gắn nhãn
                     $q['is_ready_to_save'] = $door1['is_valid'] && $door2['is_valid'];
                     $q['permission_errors'] = $door1['errors'];
                     $q['format_errors'] = $door2['errors'];

                     // SỬA LỖI 2: ĐỔI $item['question_data'] THÀNH $q
                     $q['format_warnings'] = $door2['warnings'] ?? [];

                     // Nếu 1 câu con hỏng, cả cụm Context sẽ không được lưu
                     if (! $q['is_ready_to_save']) {
                         $isContextReady = false;
                     }
                 }

                 // Gắn nhãn cho cả cụm Shared Context
                 $item['is_ready_to_save'] = $isContextReady;
             }
         }

         return $structuredData;
     } */

    /**
     * Nhạc trưởng điều phối kiểm tra dữ liệu trước khi Preview
     */
    /* public function evaluateImportData(array $structuredData, User $user): array
    {
        foreach ($structuredData as &$item) {

            if ($item['type'] === 'IndependentQuestion') {
                $objectives = $item['question_data']['objectives'] ?? [];

                // --- [CỬA 1]: Kiểm tra thẩm quyền ---
                $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

                // --- [CỬA 2]: Kiểm tra cấu trúc câu hỏi ---
                $typeCode = strtoupper(strip_tags($item['question_data']['type'] ?? ''));
                $handler = $this->getHandlerByCode($typeCode);

                // FIX NGHỊCH LÝ: Mượn tạm EssayHandler để chạy 5 quy tắc chung nếu Type bị thiếu/sai
                $actualHandler = $handler ?? app(EssayHandler::class);

                // Gọi hàm kiểm tra cấu trúc (Lúc này chắc chắn hàm validateImportData sẽ được chạy)
                $door2 = $actualHandler->validateImportData($item['question_data']);

                // Gắn nhãn kết quả
                $item['question_data']['is_ready_to_save'] = $door1['is_valid'] && $door2['is_valid'];
                $item['question_data']['permission_errors'] = $door1['errors'];
                $item['question_data']['format_errors'] = $door2['errors'];
                $item['question_data']['format_warnings'] = $door2['warnings'] ?? [];

            } elseif ($item['type'] === 'SharedContext') {
                $isContextReady = true;

                foreach ($item['questions'] as &$q) {
                    $objectives = $q['objectives'] ?? [];

                    // --- [CỬA 1] ---
                    $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

                    // --- [CỬA 2]: Kiểm tra cấu trúc câu hỏi con ---
                    $typeCode = strtoupper(strip_tags($q['type'] ?? ''));
                    $handler = $this->getHandlerByCode($typeCode);

                    // FIX NGHỊCH LÝ CHO CÂU HỎI CON
                    $actualHandler = $handler ?? app(EssayHandler::class);
                    $door2 = $actualHandler->validateImportData($q);

                    // Gắn nhãn
                    $q['is_ready_to_save'] = $door1['is_valid'] && $door2['is_valid'];
                    $q['permission_errors'] = $door1['errors'];
                    $q['format_errors'] = $door2['errors'];
                    $q['format_warnings'] = $door2['warnings'] ?? [];

                    if (! $q['is_ready_to_save']) {
                        $isContextReady = false;
                    }
                }
                $item['is_ready_to_save'] = $isContextReady;
            }
        }

        return $structuredData;
    } */

    /**
     * Bước 2: Chấm điểm (Evaluate) mảng dữ liệu đã được cấu trúc
     */
    public function evaluateImportData(array $structuredData, User $user): array
    {
        $sharedContextHandler = app(SharedContextHandler::class);

        foreach ($structuredData as &$item) {

            // TRƯỜNG HỢP 1: CÂU HỎI ĐỘC LẬP
            if ($item['type'] === 'IndependentQuestion') {
                // Ném thẳng vào Phễu kiểm tra (Cửa 1 + Cửa 2)
                $item['question_data'] = $this->evaluateSingleQuestion($item['question_data'], $user);
            }
            // TRƯỜNG HỢP 2: CÂU HỎI CHÙM (SHARED CONTEXT)
            elseif ($item['type'] === 'SharedContext') {
                $evaluatedQuestions = [];

                // 1. Lôi mọi question con trong chùm ra ném vào Phễu kiểm tra (Cửa 1 + Cửa 2)
                foreach ($item['questions'] as $q) {
                    $evaluatedQuestions[] = $this->evaluateSingleQuestion($q, $user);
                }

                // 2. Chấm điểm bản thân ông SharedContext bằng cách ném nguyên cục cho Handler xử lý
                $contextValidation = $sharedContextHandler->validateImportData($item, $evaluatedQuestions);

                // 3. Ráp kết quả kiểm tra chùm vào lại item
                $item['is_ready_to_save'] = $contextValidation['is_valid'];
                $item['errors'] = $contextValidation['errors'];
                $item['warnings'] = $contextValidation['warnings'];
                $item['questions'] = $contextValidation['questions']; // Cập nhật lại mảng con (nếu có hiệu ứng domino)
            }
        }

        return $structuredData;
    }

    /**
     * HÀM PHỄU: Kiểm tra 1 câu hỏi bất kỳ qua 2 Cửa (Quyền và Cấu trúc)
     */
    private function evaluateSingleQuestion(array $qData, User $user): array
    {
        $objectives = $qData['objectives'] ?? [];

        // ==========================================
        // CỬA 1: Kiểm tra thẩm quyền
        // ==========================================
        $door1 = $this->permissionService->verifyObjectivePermissions($objectives, $user);

        // ==========================================
        // CỬA 2: Kiểm tra cấu trúc câu hỏi
        // ==========================================
        $typeCode = strtoupper(strip_tags($qData['type'] ?? ''));
        $handler = $this->getHandlerByCode($typeCode);

        // FIX NGHỊCH LÝ: Mượn tạm EssayHandler để chạy 5 quy tắc chung nếu Type bị thiếu/sai
        $actualHandler = $handler ?? app(EssayHandler::class);
        $door2 = $actualHandler->validateImportData($qData);

        // ==========================================
        // Gắn nhãn kết quả cho câu hỏi
        // ==========================================
        $qData['is_ready_to_save'] = $door1['is_valid'] && $door2['is_valid'];
        $qData['permission_errors'] = $door1['errors'] ?? [];
        $qData['format_errors'] = $door2['errors'] ?? [];
        $qData['format_warnings'] = $door2['warnings'] ?? [];

        return $qData;
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

    /**
     * Hàm nhạc trưởng: Lưu toàn bộ mảng dữ liệu đã xử lý vào Database
     */
    public function saveStructuredData(array $structuredData, int $userId)
    {
        // dd($structuredData); // Tạm thời dừng ở đây để kiểm tra dữ liệu trước khi lưu
        return DB::transaction(function () use ($structuredData, $userId) {
            $count = 0;

            foreach ($structuredData as $item) {
                // 1. Nếu là Câu hỏi chùm
                if ($item['type'] === 'SharedContext') {
                    // Kiểm tra logic sẵn sàng lưu của câu hỏi chùm (nếu bạn có đặt cờ này ở mức context)
                    if (empty($item['is_ready_to_save'])) {
                        continue;
                    }

                    $this->saveSharedContextGroup($item, $userId);
                    $count += count($item['questions']);
                }
                // 2. Nếu là Câu hỏi độc lập
                elseif ($item['type'] === 'IndependentQuestion') {

                    // CHỖ NÀY QUAN TRỌNG: Phải trỏ vào ['question_data']
                    $qData = $item['question_data'];

                    // Kiểm tra xem câu hỏi này có hợp lệ không
                    if (empty($qData['is_ready_to_save'])) {
                        continue;
                    }
                    // dd($qData); // Tạm thời dừng ở đây để kiểm tra dữ liệu câu hỏi độc lập trước khi lưu
                    // Nếu hợp lệ thì mới đi tiếp vào đây để lưu
                    $this->saveIndependentQuestion($qData, $userId);
                    $count++;
                }
            }

            return $count;
        });
    }

    /**
     * Lưu nhóm câu hỏi có nội dung dùng chung
     */
    private function saveSharedContextGroup(array $item, int $userId)
    {
        // Gọi thẳng Handler của SharedContext và ném nguyên cục $item cho nó tự xử
        $sharedContextHandler = app(\App\QuestionHandlers\SharedContextHandler::class);
        $sharedContextHandler->storeImportData($item, $userId);
    }

    /**
     * Lưu một câu hỏi đơn lẻ (Hoặc câu hỏi con trong chùm)
     */
    private function saveIndependentQuestion(array $questionData, int $userId, ?int $sharedContextId = null)
    {
        // Tạm dừng để kiểm tra dữ liệu câu hỏi trước khi lưu
        // Xác định đúng Handler dựa trên Type (MC, TF, SA, ES)
        // dd($questionData); // Tạm thời dừng ở đây để kiểm tra dữ liệu câu hỏi trước khi lưu
        $handler = $this->getHandlerByCode($questionData['type']);

        // dd($handler); // Tạm thời dừng ở đây để kiểm tra Handler trước khi lưu câu hỏi
        // Gọi hàm "xương sống" ở BaseQuestionHandler mà chúng ta đã thống nhất
        return $handler->storeImportData($questionData, $userId, $sharedContextId);
    }
}
