<?php

namespace App\QuestionHandlers;

use App\Models\Question;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EssayHandler implements QuestionHandlerInterface
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
            if (!empty($data['objective_ids'])) {
                $question->objectives()->sync($data['objective_ids']);
            }

            // 3. Tạo hướng dẫn chấm (Dùng đúng quan hệ 'explanation')
            if (!empty($cleanExplanation)) {
                $question->explanation()->create([
                    'content' => $cleanExplanation,
                ]);
            }

            return $question;
        });
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
            if (!empty($cleanExplanation)) {
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
     * 4. Trả về cấu trúc dữ liệu chuẩn
     */
    public function getDetails(Question $question): array
    {
        // Dùng $question->explanation thay vì $question->explanations()
        $explanation = $question->explanation; 

        return [
            'type' => 'es',
            'stem' => $question->stem,
            'explanation' => $explanation ? $explanation->content : null,
        ];
    }

    /**
     * 5. Xóa câu hỏi Tự luận và các dữ liệu liên quan
     */
    public function destroy(Question $question): void
    {
        // 1. DỌN DẸP FILE ẢNH VẬT LÝ TRONG Ổ CỨNG
        // Quét và xóa ảnh trong đề bài (stem)
        if (!empty($question->stem)) {
            $this->imageService->deleteImagesFromContent($question->stem);
        }

        // Quét và xóa ảnh trong lời giải
        $explanation = $question->explanation;
        if ($explanation && !empty($explanation->content)) {
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
}