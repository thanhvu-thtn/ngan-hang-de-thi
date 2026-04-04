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