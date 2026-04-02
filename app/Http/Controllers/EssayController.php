<?php

namespace App\Http\Controllers;

use App\QuestionHandlers\EssayHandler;
use App\Services\ImageService; // Bổ sung Service vào đây
use Illuminate\Http\Request;

class EssayController extends Controller
{
    protected $handler;

    protected $imageService;

    // Tiêm cả Handler và ImageService vào Controller
    public function __construct(EssayHandler $handler, ImageService $imageService)
    {
        $this->handler = $handler;
        $this->imageService = $imageService;
    }

    /**
     * Bước 2: Hiển thị form soạn thảo nội dung Tự luận
     */
    public function create()
    {
        // Lấy dữ liệu Bước 1 từ Session
        $setupData = session('question_setup');

        // Nếu không có session (do load thẳng URL hoặc hết hạn), đá về Bước 1
        if (! $setupData) {
            return redirect()->route('questions.create')
                ->with('error', 'Dữ liệu thiết lập không tồn tại. Vui lòng làm lại Bước 1.');
        }

        // Truyền mảng $setupData ra View thay vì $question
        return view('questions.essay.create', compact('setupData'));
    }

    /**
     * Bước 2 (Xử lý): Nhận dữ liệu từ form và nhờ Handler lưu vào DB
     */
    public function store(Request $request)
    {
        // 1. Lấy dữ liệu Bước 1 từ Session
        $setupData = session('question_setup');

        // Nếu session bị mất (do để quá lâu hoặc lỗi), bắt quay lại Bước 1
        if (! $setupData) {
            return redirect()->route('questions.create')
                ->with('error', 'Phiên làm việc đã hết hạn. Vui lòng thiết lập lại câu hỏi.');
        }

        // 2. Validate dữ liệu Bước 2 (stem, explanation)
        $essayData = $this->handler->validateData($request);

        // Xử lý ảnh (Tắm rửa dữ liệu)
        $essayData['stem'] = $this->imageService->localizeImages($essayData['stem'] ?? '');
        $essayData['explanation'] = $this->imageService->localizeImages($essayData['explanation'] ?? '');

        // 3. Gộp chung dữ liệu Bước 1 và Bước 2 thành 1 mảng duy nhất
        $finalData = array_merge($setupData, $essayData);

        // 4. Nhờ Handler lưu toàn bộ vào DB
        $this->handler->store($finalData);

        // 5. Quét dọn Session cho sạch sẽ
        session()->forget('question_setup');

        return redirect()->route('questions.index')
            ->with('success', 'Đã lưu câu hỏi Tự luận thành công!');
    }
}
