<?php

if (!function_exists('make_image_paths_absolute')) {
    /**
     * Chuyển đổi các đường dẫn ảnh tương đối thành tuyệt đối
     * * @param string|null $content Đoạn mã HTML chứa hình ảnh
     * @return string
     */
    function make_image_paths_absolute($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Lấy domain hiện tại (tự động nhận diện localhost hay IP LAN)
        $currentHost = request()->getSchemeAndHttpHost();

        // 1. Gỡ hardcode localhost cũ (nếu có trong DB)
        $content = str_replace(
            ['http://localhost:8000', 'http://127.0.0.1:8000'], 
            $currentHost, 
            $content
        );

        // 2. Thay thế đường dẫn tương đối thành tuyệt đối
        $content = preg_replace_callback('/src=["\'](?!http:|https:|\/\/)(.*?)["\']/i', function($matches) use ($currentHost) {
            $path = ltrim($matches[1], '/'); 
            return 'src="' . $currentHost . '/' . $path . '"';
        }, $content);

        return $content;
    }
}

if (!function_exists('make_image_paths_local')) {
    /**
     * Chuyển đổi đường dẫn ảnh thành đường dẫn VẬT LÝ trên ổ cứng
     * Dành riêng cho việc xuất file Word, PDF để tránh lỗi mạng
     */
    function make_image_paths_local($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Lấy địa chỉ đang chạy (ví dụ: http://localhost:8000 hoặc IP)
        $currentHost = request()->getSchemeAndHttpHost();
        
        // Xóa sạch phần http://... để chừa lại chữ /storage/...
        $content = str_replace(
            [$currentHost, 'http://localhost:8000', 'http://127.0.0.1:8000'], 
            '', 
            $content
        );

        // Đổi chữ /storage/... thành đường dẫn ổ cứng (vd: C:\xampp\htdocs\...\public\storage\...)
        $content = preg_replace_callback('/src=["\']\/?(storage\/.*?)["\']/i', function($matches) {
            $localPath = public_path($matches[1]); 
            return 'src="' . $localPath . '"';
        }, $content);

        return $content;
    }
}

if (!function_exists('clean_p_tags_in_tables')) {
    /**
     * Chỉ xóa thẻ <p> nằm bên trong thẻ <td> của bảng
     */
    function clean_p_tags_in_tables(string $html): string 
    {
        if (empty(trim($html))) {
            return $html;
        }

        $dom = new DOMDocument();
        // Tắt cảnh báo lỗi HTML không chuẩn
        libxml_use_internal_errors(true);
        
        // Load HTML với UTF-8 để không bị lỗi font tiếng Việt
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        // Lấy tất cả các thẻ <td>
        $tds = $dom->getElementsByTagName('td');
        
        foreach ($tds as $td) {
            $ps = $td->getElementsByTagName('p');
            
            // Phải lặp ngược từ dưới lên khi thay đổi cấu trúc DOM
            for ($i = $ps->length - 1; $i >= 0; $i--) {
                $p = $ps->item($i);
                
                $fragment = $dom->createDocumentFragment();
                // Giữ lại nội dung bên trong thẻ <p>
                while ($p->childNodes->length > 0) {
                    $fragment->appendChild($p->childNodes->item(0));
                }
                
                // Thêm một thẻ <br> vào cuối để thay thế cho khoảng cách của thẻ <p>
                $fragment->appendChild($dom->createElement('br'));
                
                // Thay thế thẻ <p> bằng nội dung của nó + thẻ <br>
                $p->parentNode->replaceChild($fragment, $p);
            }
        }

        // Xuất ra chuỗi HTML và loại bỏ tag XML mặc định
        $result = $dom->saveHTML();
        return str_replace('<?xml encoding="UTF-8">', '', $result);
    }
}