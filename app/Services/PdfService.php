<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class PdfService
{
    public function generateFromHtml(string $html)
    {
        // 1. Tiêm script KaTeX vào HTML trước khi render
        // 1. Tiêm script KaTeX (chế độ MathML)
        $html = $this->injectKaTeX_mathml($html);

        // 2. BIẾN ẢNH THÀNH BASE64 (Giải quyết triệt để lỗi hình ảnh)
        $html = $this->convertImagesToBase64($html);

        return Browsershot::html($html)
            ->format('A4')
            ->margins(20, 20, 20, 20)
            ->showBackground()
            // BỎ LỆNH waitUntilNetworkIdle() ĐỂ TRÁNH TREO
            ->delay(1500) // Nghỉ 1.5 giây để KaTeX vẽ công thức xong
            ->pdf();
    }

    /**
     * Tự động nhúng thư viện KaTeX offline theo dạng Inline Script để chống treo máy
     */
    private function injectKaTeX_mathml(string $html): string
    {
        // 1. Đọc trực tiếp nội dung file JS từ ổ cứng (Bác nhớ check lại đúng đường dẫn nhé)
        $katexJsPath = public_path('vendor/katex/katex.min.js');
        $autoRenderJsPath = public_path('vendor/katex/contrib/auto-render.min.js'); // Hoặc public_path('vendor/katex/auto-render.min.js')

        $katexJsContent = file_exists($katexJsPath) ? file_get_contents($katexJsPath) : '';
        $autoRenderJsContent = file_exists($autoRenderJsPath) ? file_get_contents($autoRenderJsPath) : '';

        // 2. Cấu hình auto-render (Dùng Nowdoc <<<'HTML' để PHP không dịch sai dấu gạch chéo của JS)
        $autoRenderConfig = <<<'HTML'
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof renderMathInElement !== 'undefined') {
                    renderMathInElement(document.body, {
                        delimiters: [
                            {left: "$$", right: "$$", display: true},
                            {left: "\\[", right: "\\]", display: true},
                            {left: "$", right: "$", display: false},
                            {left: "\\(", right: "\\)", display: false}
                        ],
                        macros: {
                            // Giả lập package 'icomma' của LaTeX cho chuẩn Việt Nam
                            ",": function(context) {
                                // Fallback nếu phiên bản KaTeX cũ không hỗ trợ đọc context
                                if (!context || typeof context.future !== 'function') {
                                    return "\\mathord{\\char`,}";
                                }
                                
                                var nextToken = context.future();
                                // Nếu có khoảng trắng sau dấu phẩy (VD: x, y) -> Dùng chuẩn dấu câu
                                if (nextToken && nextToken.text === " ") {
                                    return "\\mathpunct{\\char`,}";
                                }
                                // Nếu viết liền (VD: 5,0) -> Dùng chuẩn ký tự thông thường (xoá khoảng cách)
                                return "\\mathord{\\char`,}";
                            }
                        },
                        throwOnError: false, // Bỏ qua lỗi cú pháp
                        output: "mathml"     // Chỉ xuất ra thẻ <math> của MathML
                    });
                }
            });
        </script>
        HTML;

        // 3. Ghép toàn bộ thành script nội tuyến (Inline)
        $katexScripts = <<<HTML
        <script>{$katexJsContent}</script>
        <script>{$autoRenderJsContent}</script>
        {$autoRenderConfig}
        HTML;

        // 4. Chèn vào HTML
        if (str_contains($html, '</head>')) {
            return str_replace('</head>', $katexScripts.'</head>', $html);
        }

        return $html.$katexScripts;
    }

    /**
     * Tự động quét các thẻ <img> và chuyển src từ URL/Path sang Base64
     */
    private function convertImagesToBase64(string $html): string
    {
        return preg_replace_callback('/<img [^>]*src=["\']([^"\']+)["\'][^>]*>/i', function ($matches) {
            $fullTag = $matches[0];
            $url = $matches[1];

            // Nếu ảnh đã là base64 rồi thì bỏ qua
            if (str_starts_with($url, 'data:')) {
                return $fullTag;
            }

            // Tìm đường dẫn thực tế trên ổ cứng của ảnh
            // Giả sử ảnh của bác nằm trong: storage/app/public/uploads/... 
            // và URL là: http://localhost:8000/storage/uploads/...
            $absolutePath = '';
            
            if (str_contains($url, '/storage/')) {
                $relativePath = last(explode('/storage/', $url));
                $absolutePath = public_path('storage/' . $relativePath);
            }

            // Nếu tìm thấy file, tiến hành mã hóa
            if (!empty($absolutePath) && File::exists($absolutePath)) {
                $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
                $data = File::get($absolutePath);
                $base64 = 'data:image/' . $extension . ';base64,' . base64_encode($data);
                
                return str_replace($url, $base64, $fullTag);
            }

            return $fullTag;
        }, $html);
    }
    
}
