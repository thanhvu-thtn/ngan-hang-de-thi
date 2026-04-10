<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// SỬA DÒNG NÀY: extends BaseQuestionHandler
class EssayHandler extends BaseQuestionHandler
{
    protected ImageService $imageService;

    /**
     * Inject ImageService để xử lý ảnh trong nội dung TinyMCE
     */
    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * 1. Validate dữ liệu gửi lên từ Form Bước 2 (Tự luận)
     */
    public function validateData(Request $request): array
    {
        return $request->validate([
            'stem' => 'required|string',
            'explanation' => 'nullable|string',
        ], [
            'stem.required' => 'Nội dung câu hỏi (đề bài) không được để trống.',
        ]);
    }

    /**
     * 2. Lưu chi tiết câu hỏi
     * Lưu ý: Sửa lỗi dùng sai tên quan hệ 'explanations' thành 'explanation'
     */
    public function store(array $data): Question
    {
        return DB::transaction(function () use ($data) {
            // Xử lý lưu ảnh vào local trước khi lưu vào DB
            $cleanStem = $this->imageService->localizeImages($data['stem']);
            $cleanExplanation = isset($data['explanation'])
                                ? $this->imageService->localizeImages($data['explanation'])
                                : null;

            // 1. Tạo câu hỏi mới
            $question = Question::create([
                'name' => $data['name'],
                'tag_name' => $data['tag_name'],
                'question_type_id' => $data['type'],
                'cognitive_level_id' => $data['level'],
                'stem' => $cleanStem,
            ]);

            // 2. Gắn mục tiêu đánh giá (từ dữ liệu Bước 1)
            if (! empty($data['objective_ids'])) {
                $question->objectives()->sync($data['objective_ids']);
            }

            // 3. Tạo hướng dẫn chấm (Dùng đúng quan hệ 'explanation')
            if (! empty($cleanExplanation)) {
                $question->explanation()->create([
                    'content' => $cleanExplanation,
                ]);
            }

            return $question;
        });
    }

    /**
     * Xử lý ảnh và lưu câu hỏi Tự luận
     */
    public function storeQuestion(array $commonData, array $specificData): Question
    {
        // 1. Xử lý bóc tách ảnh Base64 ra file vật lý
        $cleanStem = $this->imageService->localizeImages($specificData['stem']);

        $cleanExplanation = isset($specificData['explanation'])
                            ? $this->imageService->localizeImages($specificData['explanation'])
                            : null;

        // 2. Gộp nội dung Đề bài vào mảng Dữ liệu chung
        $questionData = array_merge($commonData, [
            'stem' => $cleanStem,
        ]);

        // 3. Thực thi Create (Lúc này có đủ tag_name, name, và stem -> DB không báo lỗi nữa)
        $question = Question::create($questionData);

        // 4. Tạo hướng dẫn chấm (Explanation) nếu có
        if (! empty($cleanExplanation)) {
            $question->explanation()->create([
                'content' => $cleanExplanation,
            ]);
        }

        return $question; // Trả object question về cho Controller gắn Objectives
    }

    /**
     * 3. Cập nhật chi tiết câu hỏi (Khi Edit)
     */
    public function update(Question $question, array $validatedData): Question
    {
        return DB::transaction(function () use ($question, $validatedData) {
            // Xử lý ảnh trong nội dung cập nhật
            $cleanStem = $this->imageService->localizeImages($validatedData['stem']);
            $cleanExplanation = isset($validatedData['explanation'])
                                ? $this->imageService->localizeImages($validatedData['explanation'])
                                : null;

            // Cập nhật đề bài
            $question->update([
                'stem' => $cleanStem,
            ]);

            // Cập nhật hoặc tạo mới lời giải (Dùng đúng quan hệ 'explanation')
            if (! empty($cleanExplanation)) {
                $question->explanation()->updateOrCreate(
                    ['question_id' => $question->id],
                    ['content' => $cleanExplanation]
                );
            } else {
                $question->explanation()->delete();
            }

            return $question;
        });
    }

    /**
     * Xử lý ảnh và Cập nhật câu hỏi Tự luận
     */
    public function updateQuestion(Question $question, array $commonData, array $specificData): void
    {
        // 1. Xử lý ảnh cho nội dung mới (ImageService sẽ tự lo việc không tải lại ảnh đã có)
        $cleanStem = $this->imageService->localizeImages($specificData['stem']);

        // 2. Gộp nội dung Đề bài vào mảng Dữ liệu chung và Update
        $questionData = array_merge($commonData, [
            'stem' => $cleanStem,
        ]);

        $question->update($questionData);

        // 3. Xử lý Lời giải
        if (isset($specificData['explanation']) && ! empty($specificData['explanation'])) {
            $cleanExplanation = $this->imageService->localizeImages($specificData['explanation']);

            // Dùng updateOrCreate để: Có thì sửa, chưa có thì thêm mới
            $question->explanation()->updateOrCreate(
                ['question_id' => $question->id],
                ['content' => $cleanExplanation]
            );
        } else {
            // Nếu gửi lên rỗng, ta xóa lời giải cũ đi (nếu có)
            $question->explanation()->delete();
        }
    }

    /**
     * 4. Trả về cấu trúc dữ liệu chuẩn
     */


    /**
     * 5. Xóa câu hỏi Tự luận và các dữ liệu liên quan
     */
    public function destroy(Question $question): void
    {
        // 1. DỌN DẸP FILE ẢNH VẬT LÝ TRONG Ổ CỨNG
        // Quét và xóa ảnh trong đề bài (stem)
        if (! empty($question->stem)) {
            $this->imageService->deleteImagesFromContent($question->stem);
        }

        // Quét và xóa ảnh trong lời giải
        $explanation = $question->explanation;
        if ($explanation && ! empty($explanation->content)) {
            $this->imageService->deleteImagesFromContent($explanation->content);
        }

        // 2. XÓA DỮ LIỆU TRONG DATABASE (Dùng Transaction để đảm bảo an toàn)
        DB::transaction(function () use ($question) {

            // Xóa tất cả các lựa chọn (Đề phòng rác dữ liệu nếu trước đó là câu trắc nghiệm)
            if (method_exists($question, 'choices')) {
                $question->choices()->delete();
            }

            // Xóa lời giải
            if (method_exists($question, 'explanation')) {
                $question->explanation()->delete();
            }

            // Bảng trung gian objective_question (nếu bạn không dùng ON DELETE CASCADE)
            if (method_exists($question, 'objectives')) {
                $question->objectives()->detach();
            }

            // Cuối cùng, xóa câu hỏi gốc
            $question->delete();
        });
    }

    /**
     * Validate dữ liệu khi Import (từ mảng, không dùng Request)
     */
    /**
     * Validate dữ liệu khi Import (từ mảng, không dùng Request)
     */
    /**
     * Validate dữ liệu khi Import (từ mảng, không dùng Request)
     */

    // -----------------------------------------------------------------------------
    //
    // CODE MỚI - KẾ THỪA TỪ BASEQUESTIONHANDLER (Có thể có thêm các hàm phụ trợ nếu cần)
    // -----------------------------------------------------------------------------

    // LƯU Ý: Các hàm dưới đây là phần "xương sống" đã được chuẩn hóa trong BaseQuestionHandler, các Handler con sẽ gọi lại để tận dụng chung logic và chỉ cần bổ sung phần riêng nếu có

    /**
     * Validate dữ liệu riêng cho câu hỏi Tự luận (ES)
     */
    protected function validateSpecificImportData(array $questionData): array
    {
        $warnings = [];

        // Cảnh báo: Nếu tự luận mà lại có Lựa chọn (Choices)
        if (! empty($questionData['choices'])) {
            $warnings[] = 'Phát hiện có Lựa chọn đáp án (Choices). Câu hỏi Tự luận (ES) không cần dữ liệu này, khi lưu câu hỏi vào hệ thống, phần này sẽ bị bỏ qua.';
        }

        return [
            'is_valid' => true, // Luôn true vì phần này chỉ có cảnh báo, không có lỗi đánh rớt
            'errors' => [],   // Không có lỗi nghiêm trọng nào phát sinh từ phần riêng này
            'warnings' => $warnings,
        ];
    }

    /**
     * Hàm dùng chung để lưu câu hỏi từ Form Bước 2 (Tự luận)
     */
    protected function storeSpecificImportData(Question $question, array $questionData): void
    {
        // dd($questionData); // Tạm thời dừng ở đây để kiểm tra dữ liệu câu hỏi trước khi lưu
        // Tự luận không có choices, có thể bỏ qua hoặc lưu đáp án mẫu vào 1 bảng khác nếu có
    }

    /// Hàm dùng chung để lấy chi tiết câu hỏi khi Show hoặc Edit
    protected function getSpecificDetails(Question $question): array
    {
        // Tự luận thường không có choices hay dữ liệu riêng phức tạp
        return [
            'choices' => [],
        ];
    }
}
