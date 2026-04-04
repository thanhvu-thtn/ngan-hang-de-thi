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
        $question=$this->handler->store($finalData);

        // 5. Quét dọn Session cho sạch sẽ
        session()->forget('question_setup');

        return redirect()->route('questions.es.edit', $question->id)
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
    {
        // 1. Validate dữ liệu gửi lên
        $request->validate([
            'stem' => 'required|string',
            'explanation' => 'nullable|string',
            'status' => 'nullable|integer|in:0,1,2',
        ], [
            'stem.required' => 'Bạn chưa nhập nội dung đề bài.',
        ]);
        // 1. Lấy dữ liệu từ request
        $stemContent = $request->input('stem');
        $explanationContent = $request->input('explanation');

        // 2. Tắm rửa dữ liệu ảnh (Kéo hình mới về server nếu có)
        $stemContent = $this->imageService->localizeImages($stemContent ?? '');
        $explanationContent = $this->imageService->localizeImages($explanationContent ?? '');

        // 2. Tìm câu hỏi
        $question = Question::findOrFail($id);

        // 3. Cập nhật dữ liệu chính (stem)
        $question->stem = $stemContent;

        // 4. KIỂM TRA QUYỀN: Chỉ lưu status nếu có quyền 'tham-dinh-cau-hoi'
        // Dùng auth()->user()->can() hoặc Gate::allows() đều được
        if ($request->user()->can('tham-dinh-cau-hoi')) {
            $question->status = $request->input('status', $question->status);
        }

        // Lưu bảng questions
        $question->save();

        // 5. Cập nhật bảng con (question_explanations)
        // Dùng updateOrCreate: Tìm theo question_id, nếu có thì update content, chưa có thì tạo mới
        if ($request->filled('explanation')) {
            $question->explanation()->updateOrCreate(
                ['question_id' => $question->id],
                ['content' => $explanationContent]
            );
        } else {
            // Nếu người dùng xóa trống ô lời giải, ta cập nhật thành null
            $question->explanation()->delete();

        }

        // 6. Trả về thông báo thành công
        // Bạn có thể redirect về lại trang edit hiện tại, hoặc redirect về trang danh sách câu hỏi tùy ý
        return redirect()->route('questions.es.edit', $question->id)->with('success', 'Cập nhật câu hỏi Tự luận thành công!');
    }

    /**
     * Xuất PDF xem trước cho Câu hỏi Tự luận
     */
    public function printPdf($id, PdfService $pdfService)
    {
        // 1. Lấy câu hỏi và load kèm lời giải (tránh lỗi N+1)
        $question = Question::findOrFail($id);
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

        // 2. Render view thành một chuỗi HTML dạng string
        // Thay vì trả về view thẳng cho người dùng, ta lưu nó vào biến $html
        $html = view('questions.essay.partials.pdf_template', compact('question'))->render();

        // 2. Dùng DOMDocument để "dọn dẹp" và hoàn thiện các tag HTML
        $dom = new \DOMDocument;

        // Bỏ qua các cảnh báo lỗi HTML vặt vãnh
        libxml_use_internal_errors(true);

        // Load chuỗi HTML vào (phải convert sang HTML-ENTITIES để không bị lỗi font tiếng Việt)
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));

        // Xóa bộ nhớ đệm lỗi
        libxml_clear_errors();

        // Lấy chuỗi HTML đã được tự động sửa lỗi và đóng tag
        $cleanHtml = $dom->saveHTML();
        //return ($cleanHtml);
        // 3. Sử dụng PdfService để tạo nội dung file PDF từ chuỗi HTML
        $pdfContent = $pdfService->generateFromHtml($cleanHtml);

        // 4. Trả file PDF về cho trình duyệt
        $fileName = 'cau_hoi_tu_luan_'.$question->id.'.pdf';

        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            // Dùng 'inline' để mở tab mới xem PDF.
            // Nếu muốn tải thẳng về máy, thay chữ 'inline' thành 'attachment'
            'Content-Disposition' => 'inline; filename="'.$fileName.'"',
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
