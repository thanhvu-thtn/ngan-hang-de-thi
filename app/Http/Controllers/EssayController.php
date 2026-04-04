<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\QuestionHandlers\EssayHandler; // Bổ sung Service vào đây
use App\Services\ImageService;
use App\Services\PdfService; // Bổ sung Service vào đây
use App\Services\WordService; // Bổ sung Service vào đây
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

    // Hàm show để hiển thị câu hỏi đã tạo (nếu cần)
    public function show($id)
    {
        // 1. Tìm câu hỏi dựa trên ID truyền vào (sẽ báo lỗi 404 nếu không thấy)
        $question = Question::findOrFail($id);

        // 2. Kiểm tra nếu câu hỏi này thuộc về một Dữ liệu dùng chung (Shared Context)
        if (! empty($question->shared_context_id)) {
            // CÁCH 1: Redirect hẳn sang route của SharedContext (Khuyên dùng - Đổi URL)
            // Giả sử bạn có đặt tên route là 'shared_contexts.show'
            // return redirect()->route('shared_contexts.show', $question->shared_context_id);

            // CÁCH 2: Gọi trực tiếp method show của SharedContextController mà KHÔNG đổi URL
            // return app()->call([SharedContextController::class, 'show'], ['id' => $question->shared_context_id]);
            return 'Câu hỏi này thuộc về loại có dữ liệu dùng chung, id shared context là '.$question->shared_context_id;
        }

        // 3. Nếu KHÔNG có shared_context_id -> TRUY VẤN TẤT TẦN TẬT
        // Sử dụng hàm ->load() (Lazy Eager Loading) để lấy tất cả các mối quan hệ
        // đã được định nghĩa trong Model Question, giúp tránh lỗi N+1 Query.
        $question->load([
            'questionType',
            'cognitiveLevel',
            'layout',
            'choices',
            'statistic',
            'objectives',
            'explanation',
            'checker',
        ]);

        // ==========================================
        // SAU KHI TEST XONG, BẠN XÓA PHẦN TEST VÀ MỞ COMMENT DÒNG DƯỚI ĐỂ CHẠY VIEW
        // ==========================================
        return view('questions.essay.show', compact('question'));
    }

    // hàm edit - Hiện thông số để chỉnh sửa
    public function edit($id)
    {
        // 1. Lấy tất tần tật dữ liệu của câu hỏi và các bảng con (Eager Loading)
        // Lưu ý: Tùy vào cách bạn thiết kế Database, bạn có thể thêm/bớt các relation ở trong mảng with()
        $question = Question::with([
            'questionType',    // Loại câu hỏi
            'cognitiveLevel',  // Mức độ nhận thức
            'objectives',      // Mục tiêu đánh giá (bảng trung gian)
            'explanation',     // Lời giải chi tiết
            'choices',          // Các đáp án (nếu Tự luận của bạn dùng bảng answers để lưu đáp án mẫu)
            'checker',          // Người thẩm định (nếu có)
        ])->findOrFail($id);

        // 2. Kiểm tra nếu là câu hỏi chùm (có nội dung dùng chung)
        if ($question->shared_context_id) {
            return redirect()->route('shared_contexts.edit', ['id' => $question->shared_context_id])
                ->with('info', 'Câu hỏi này thuộc một ngữ cảnh dùng chung. Đang chuyển hướng sang trang chỉnh sửa đoạn văn...');
        }

        // 3. Nếu là câu hỏi đơn lẻ -> Dump dữ liệu ra màn hình cho bạn xem để tính bước tiếp theo
        // 3. Trả về view edit và truyền biến $question sang (Thay thế cho dd)
        return view('questions.essay.edit', compact('question'));
    }

    // Hàm update - Nhận dữ liệu từ form edit và cập nhật vào DB
    public function update(Request $request, $id)
    {        // 1. Tìm câu hỏi cần cập nhật
        
    }

    /**
     * Xuất PDF xem trước cho Câu hỏi Tự luận
     */
    public function printPdf($id, PdfService $pdfService)
    {
        // 1. LẤY DỮ LIỆU TỪ KHO
        // Dùng with() để Eager Load bảng explanation (lời giải) phòng trường hợp in bị lỗi N+1
        $question = Question::with('explanation')->findOrFail($id);

        // 2. LẮP RÁP VÀO KHUÔN (Render HTML)
        // Gọi view printpdf mà chúng ta vừa làm, truyền data vào và dùng hàm render() để biến thành chuỗi HTML
        $html = view('questions.essay.printpdf', compact('question'))->render();

        // dd($html); // TEST: Xem chuỗi HTML đã được render có đúng ý không trước khi đưa cho PDF Service
        // 3. GIAO CHO MÁY IN
        // Chuyển ../storage thành http://localhost:8000/storage
        $html = str_replace('../storage', asset('storage'), $html);

        // Chuyền cục HTML đó cho Service để nó chạy Browsershot tạo PDF
        $pdfContent = $pdfService->generateFromHtml($html);

        // dd($pdfContent); // TEST: Xem dữ liệu nhị phân của PDF có được trả về không (nếu thấy là chuỗi ký tự lộn xộn thì đã thành công)

        // 4. TRẢ HÀNG CHO KHÁCH (MỞ TRÊN TAB MỚI)
        // Trả về file PDF cho trình duyệt tải xuống
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'cau-hoi-'.$id.'.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    public function printWord($id, WordService $wordService)
    {
        // 1. LẤY DỮ LIỆU TỪ KHO
        // Tương tự PDF, lấy câu hỏi và lời giải để tránh lỗi N+1
        $question = Question::with('explanation')->findOrFail($id);

        // 2. LẮP RÁP VÀO KHUÔN (Render HTML)
        // Bạn có thể dùng chung view 'questions.essay.printpdf'
        // vì Pandoc xử lý HTML sang Word rất tốt.
        $html = view('questions.essay.printpdf', compact('question'))->render();

        // 3. XỬ LÝ ĐƯỜNG DẪN ẢNH
        // Chuyển đường dẫn tương đối thành tuyệt đối để WordService/Pandoc có thể nhúng ảnh vào file
        $html = str_replace('../storage', asset('storage'), $html);

        // 4. GIAO CHO WORD SERVICE
        // Gọi hàm generateFromHtml của WordService để nhận về dữ liệu nhị phân của file .docx
        $wordContent = $wordService->generateFromHtml($html);

        // 5. TRẢ HÀNG CHO KHÁCH (TẢI FILE WORD)
        // Trình duyệt sẽ nhận diện đây là file Word và tự động tải về
        return response()->streamDownload(function () use ($wordContent) {
            echo $wordContent;
        }, 'cau-hoi-'.$id.'.docx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ]);
    }
}
