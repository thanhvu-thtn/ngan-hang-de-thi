<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WordService
{
    /**
     * Tạo file Word (.docx) từ chuỗi HTML
     */
    public function generateFromHtml(string $html)
    {
        // 1. Tạo thư mục tạm nếu chưa có
        $tempDir = storage_path('app/temp_word');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // 2. Tạo tên file ngẫu nhiên
        $fileName = Str::random(10);
        $htmlFile = $tempDir.'/'.$fileName.'.html';
        $wordFile = $tempDir.'/'.$fileName.'.docx';

        // 3. Xử lý đường dẫn ảnh tuyệt đối (Pandoc cần đường dẫn vật lý để nhúng ảnh vào Word)
        // $html = str_replace(asset('storage'), public_path('storage'), $html);
        $html = make_image_paths_local($html);

        // MỚI THÊM: Xóa thẻ <p> trong bảng để Pandoc không bị vỡ table
        $html = clean_p_tags_in_tables($html);

        // Lưu file HTML tạm
        File::put($htmlFile, $html);

        // Đường dẫn tuyệt đối tới file template Word của bạn
        $templatePath = storage_path('app/pandoc/custom-reference.docx');

        // KIỂM TRA TRƯỚC XEM FILE TEMPLATE CÓ TỒN TẠI KHÔNG
        if (! File::exists($templatePath)) {
            throw new \Exception('Lỗi: Không tìm thấy file template tại đường dẫn: '.$templatePath);
        }

        // Lệnh Pandoc mới
        $command = 'pandoc '.escapeshellarg($htmlFile).
         ' -o '.escapeshellarg($wordFile).
         ' --from html+tex_math_dollars+tex_math_single_backslash '.
         ' --standalone '.
         ' --reference-doc='.escapeshellarg($templatePath);

        shell_exec($command);

        // 5. Kiểm tra và trả file về cho Controller, sau đó xóa file tạm
        if (File::exists($wordFile)) {
            $content = File::get($wordFile);

            // Xóa rác sau khi xong
            File::delete([$htmlFile, $wordFile]);

            return $content;
        }

        throw new \Exception('Pandoc không thể tạo được file Word. Kiểm tra lại nội dung HTML hoặc cài đặt Pandoc.');
    }

    /**
     * Dịch file Word (.docx) sang HTML, trích xuất ảnh và công thức Toán
     */
    public function convertWordToHtml(string $filePath)
    {
        // 1. Cấu hình thư mục chứa ảnh (nằm trong public để trình duyệt đọc được)
        $mediaDir = storage_path('app/public/temp_media');
        if (! File::exists($mediaDir)) {
            File::makeDirectory($mediaDir, 0755, true);
        }

        // File HTML tạm thời
        $htmlFile = storage_path('app/temp_word/'.Str::random(10).'.html');

        // 2. Lệnh Pandoc:
        // -t html: Xuất ra định dạng HTML
        // --extract-media: Tự động bóc tách ảnh trong Word lưu vào thư mục temp_media
        // --mathjax: Chuyển công thức MathType/Equation thành chuẩn MathJax ($$ ... $$)
        $command = 'pandoc '.escapeshellarg($filePath).
                   ' -t html '.
                   ' --extract-media='.escapeshellarg($mediaDir).
                   ' --mathjax '.
                   ' -o '.escapeshellarg($htmlFile);

        shell_exec($command);

        if (! File::exists($htmlFile)) {
            throw new \Exception('Pandoc không thể xử lý file Word này. Vui lòng kiểm tra lại định dạng file.');
        }

        // 3. Đọc nội dung HTML
        $html = File::get($htmlFile);
        File::delete($htmlFile); // Đọc xong thì xoá luôn file HTML tạm

        // 4. Sửa lại đường dẫn ảnh cho đúng URL trên Web
        // Pandoc sẽ nhúng đường dẫn vật lý vào HTML (VD: storage/app/public/temp_media/media/image1.png)
        // Ta cần đổi nó thành đường dẫn Web (VD: /storage/temp_media/media/image1.png)
        // Lưu ý dùng DIRECTORY_SEPARATOR để code chạy đúng trên cả Mac, Linux, Windows
        $physicalPath = 'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'temp_media';
        $webPath = '/storage/temp_media';
        $html = str_replace($physicalPath, $webPath, $html);

        return $html;
    }

    /**
     * Chuyển đổi bảng HTML (2 cột) thành chuỗi JSON dạng phẳng (Raw Data).
     * Chỉ làm nhiệm vụ ánh xạ Cột 1 (Field) và Cột 2 (Content).
     */
    public function htmlToJson($html)
    {
        $dom = new \DOMDocument;
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'), LIBXML_NOERROR);

        $rows = $dom->getElementsByTagName('tr');

        if ($rows->length === 0) {
            return json_encode(['error' => 'Không tìm thấy Table hợp lệ.'], JSON_UNESCAPED_UNICODE);
        }

        $data = [];

        foreach ($rows as $row) {
            $cols = [];
            foreach ($row->childNodes as $child) {
                if ($child->nodeName === 'td' || $child->nodeName === 'th') {
                    $cols[] = $child;
                }
            }

            if (count($cols) < 2) {
                continue;
            }

            // Cột 1: Field (Chỉ lấy Text, dọn sạch khoảng trắng tàng hình)
            $fieldRaw = strip_tags($cols[0]->nodeValue);
            $fieldRaw = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $fieldRaw);
            $field = trim($fieldRaw);

            // Bỏ qua dòng trống hoặc dòng tiêu đề Fields - Contents
            if (empty($field) || strtolower($field) === 'fields') {
                continue;
            }

            // Cột 2: Content (Lấy toàn bộ HTML để Handler tự quyết định cắt hay giữ)
            $contentHtml = '';
            foreach ($cols[1]->childNodes as $child) {
                $contentHtml .= $dom->saveHTML($child);
            }
            $content = trim($contentHtml);

            // Dọn rác thẻ rỗng hoặc khoảng trắng dư thừa ở content nếu có
            $content = preg_replace('/^<p>\s*<\/p>$/i', '', $content);

            $data[] = [
                'field' => $field,
                'content' => $content,
            ];
        }

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Hàm phụ trợ: Rút trích giá trị dạng Text thường
     */
    private function extractStringValue($startMarker, $endMarker, $content)
    {
        $startPos = strpos($content, '[['.$startMarker.']]');
        if ($startPos === false) {
            return '';
        }
        $startPos += strlen('[['.$startMarker.']]');

        $endPos = $endMarker ? strpos($content, '[['.$endMarker.']]', $startPos) : strlen($content);
        if ($endPos === false) {
            $endPos = strlen($content);
        }

        $text = substr($content, $startPos, $endPos - $startPos);

        return trim(strip_tags(str_replace('</p>', '', $text)));
    }

    /**
     * Hàm phụ trợ: Rút trích giá trị dạng HTML
     */
    private function extractHtmlValue($startMarker, $endMarker, $content)
    {
        $startPos = strpos($content, '[['.$startMarker.']]');
        if ($startPos === false) {
            return '';
        }
        $startPos += strlen('[['.$startMarker.']]');

        $endPos = $endMarker ? strpos($content, '[['.$endMarker.']]', $startPos) : strlen($content);
        if ($endPos === false) {
            $endPos = strlen($content);
        }

        $html = substr($content, $startPos, $endPos - $startPos);
        $html = preg_replace('/^<\/p>/i', '', trim($html));

        return trim($html);
    }
}
