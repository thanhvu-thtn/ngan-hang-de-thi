<?php

namespace App\QuestionHandlers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SharedContext;

class SharedContextHandler 
{
    // Class đặc biệt này sẽ quản lý 'shared_contexts' và 
    // một mảng các câu hỏi con (ví dụ: 4 câu TF) thuộc về nó.

    public function validateData(Request $request)
    {
        // TODO: Validate đoạn văn dùng chung và mảng các câu hỏi con từ form gửi lên
    }

    public function save($questionData, $choicesData)
    {
        // TODO: Logic lưu Shared Context và lặp qua để lưu các câu hỏi con
    }

    public function renderForm()
    {
        // TODO: Trả về form nhập nhóm câu hỏi
    }

    /**
     * Hàm Validate dành riêng cho quá trình Import file
     */
    public function validateImportData(array $contextData, array $evaluatedQuestions): array
    {
        $isValid = true;
        $errors = [];
        $warnings = [];

        // 1. CẢNH BÁO: Kiểm tra Tag Name
        $tagName = trim($contextData['context_data']['tag'] ?? '');
        if (empty($tagName)) {
            $warnings[] = 'Nhóm câu hỏi chưa có mã định danh (Hệ thống sẽ tự động cấp mã).';
        } else {
            $isExist = \App\Models\SharedContext::where('tag_name', $tagName)->exists();
            if ($isExist) {
                $warnings[] = "Mã định danh nhóm '{$tagName}' đã tồn tại (Sẽ tự động thêm hậu tố).";
            }
        }

        // 2. ĐIỀU KIỆN 1: Bắt buộc phải có Content
        $content = trim($contextData['context_data']['content'] ?? '');
        if (empty($content)) {
            $isValid = false;
            $errors[] = 'Nhóm câu hỏi bắt buộc phải có Nội dung (Content) dùng chung.';
        }

        // 3. ĐIỀU KIỆN 2: Phải có ít nhất 1 câu hỏi con hợp lệ
        $validChildrenCount = 0;
        foreach ($evaluatedQuestions as $q) {
            if (!empty($q['is_ready_to_save'])) {
                $validChildrenCount++;
            }
        }

        if ($validChildrenCount === 0) {
            $isValid = false;
            $errors[] = 'Nhóm câu hỏi phải có ít nhất 1 câu hỏi con hợp lệ (qua được cả cửa kiểm tra Quyền và Cấu trúc).';
        }

        // 4. HIỆU ỨNG DOMINO: Nếu SharedContext rớt -> Đánh rớt toàn bộ câu hỏi con
        if (!$isValid) {
            foreach ($evaluatedQuestions as &$q) {
                $q['is_ready_to_save'] = false;
                $q['format_errors'][] = 'Câu hỏi bị hủy do Nhóm câu hỏi cha không đủ điều kiện (thiếu Nội dung dùng chung hoặc không có câu con nào hợp lệ).';
            }
        }

        return [
            'is_valid'  => $isValid,
            'errors'    => $errors,
            'warnings'  => $warnings,
            'questions' => $evaluatedQuestions,
        ];
    }


    /**
     * Nhận "nguyên cục" dữ liệu SharedContext từ Service và tự xử lý lưu cha + con
     */
    public function storeImportData(array $item, int $userId)
    {
        // ==========================================
        // 1. XỬ LÝ VÀ LƯU "ÔNG CHA" (SHARED CONTEXT)
        // ==========================================
        $tagName = trim($item['context_data']['tag_name'] ?? '');
        
        if (empty($tagName)) {
            $tagName = (string) Str::uuid();
        } else {
            $isExist = SharedContext::where('tag_name', $tagName)->exists();
            if ($isExist) {
                $tagName = $tagName . '-' . substr((string) Str::uuid(), 0, 8);
            }
        }

        // Tạo mới SharedContext (Mình tạm giả định bảng của bạn có cột user_id nhé, nếu không có bạn bỏ dòng đó đi)
        $sharedContext = SharedContext::create([
            'tag_name' => $tagName,
            'content'  => $item['context_data']['content'],
            'note'     => $item['context_data']['note'] ?? null,
            // 'user_id'  => $userId, // Mở comment nếu database có cột này
        ]);

        // ==========================================
        // 2. LẶP QUA VÀ GỌI HANDLER LƯU "ĐÁM CON"
        // ==========================================
        foreach ($item['questions'] as $qData) {
            // Chỉ lưu những câu đã qua được vòng kiểm duyệt
            if (!empty($qData['is_ready_to_save'])) {
                
                // Trích xuất Type để gọi đúng Handler
                $handler = $this->getHandlerByCode($qData['type']);
                
                // Gọi Handler của câu con, truyền ID của cha vào
                $handler->storeImportData($qData, $userId, $sharedContext->id);
            }
        }

        return $sharedContext;
    }

    /**
     * Helper: Mapping mã loại câu hỏi sang Handler tương ứng
     */
    private function getHandlerByCode($code)
    {
        return match ($code) {
            'ES' => app(\App\QuestionHandlers\EssayHandler::class),
            'MC' => app(\App\QuestionHandlers\MultipleChoiceHandler::class),
            'TF' => app(\App\QuestionHandlers\TrueFalseHandler::class),
            'SA' => app(\App\QuestionHandlers\ShortAnswerHandler::class),
            default => throw new Exception('Không tìm thấy bộ xử lý cho loại câu hỏi con này.'),
        };
    }
}