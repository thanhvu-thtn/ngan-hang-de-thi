<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageService
{
    public function localizeImages(?string $htmlContent): ?string
    {
        if (empty($htmlContent)) {
            return $htmlContent;
        }

        $dom = new \DOMDocument;
        @$dom->loadHTML(mb_convert_encoding($htmlContent, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $dom->getElementsByTagName('img');
        $hasChanges = false;

        foreach ($images as $img) {
            $src = $img->getAttribute('src');

            // 1. XỬ LÝ ẢNH NGOẠI LAI (Copy từ web khác)
            if (Str::startsWith($src, ['http://', 'https://']) && !Str::contains($src, request()->getHost())) {
                try {
                    $imageContents = file_get_contents($src);
                    if ($imageContents) {
                        $extension = pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'png';
                        $filename = Str::uuid() . '.' . $extension;
                        
                        // SỬA LẠI ĐƯỜNG DẪN Ở ĐÂY CHO ĐÚNG THỐNG NHẤT
                        $folder = 'uploads/' . date('Y/m/d'); 
                        
                        $path = $folder . '/' . $filename;

                        Storage::disk('public')->put($path, $imageContents);
                        $img->setAttribute('src', asset('storage/' . $path));
                        $hasChanges = true;
                    }
                } catch (\Exception $e) {
                    Log::warning('Không thể tải ảnh từ link: ' . $src . ' - Lỗi: ' . $e->getMessage());
                }
            } 
            // 2. XỬ LÝ ẢNH BASE64 (Tải lên từ máy tính qua TinyMCE)
            elseif (Str::startsWith($src, 'data:image')) {
                try {
                    // Tách header base64 (VD: data:image/png;base64,iVBORw...)
                    list($type, $data) = explode(';', $src);
                    list(, $data)      = explode(',', $data);
                    
                    // Giải mã chuỗi thành file vật lý
                    $imageContents = base64_decode($data);

                    // Lấy đuôi file (VD: image/png -> png)
                    $extension = explode('/', $type)[1];
                    if ($extension == 'jpeg') $extension = 'jpg';

                    $filename = Str::uuid() . '.' . $extension;
                    
                    // SỬA LẠI ĐƯỜNG DẪN Ở ĐÂY NỮA
                    $folder = 'uploads/' . date('Y/m/d'); 
                    
                    $path = $folder . '/' . $filename;

                    // Lưu vào storage
                    Storage::disk('public')->put($path, $imageContents);

                    // Cập nhật lại HTML bằng link thật
                    $img->setAttribute('src', asset('storage/' . $path));
                    $hasChanges = true;
                } catch (\Exception $e) {
                    Log::warning('Lỗi xử lý ảnh Base64: ' . $e->getMessage());
                }
            }
        }

        return $hasChanges ? $dom->saveHTML() : $htmlContent;
    }
}