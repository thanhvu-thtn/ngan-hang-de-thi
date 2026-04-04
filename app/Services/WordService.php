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
        //$html = str_replace(asset('storage'), public_path('storage'), $html);
        $html = make_image_paths_local($html);

        // Lưu file HTML tạm
        File::put($htmlFile, $html);

        // 4. Chạy lệnh Pandoc để xuất Word
        // --from html+... : Nhận diện công thức toán
        // --standalone : Tạo một file Word hoàn chỉnh có cấu trúc chuẩn
        // $command = 'pandoc '.escapeshellarg($htmlFile).
        //    ' -o '.escapeshellarg($wordFile).
        //    ' --from html+tex_math_dollars+tex_math_single_backslash '.
        //    ' --standalone';

        $command = 'pandoc '.escapeshellarg($htmlFile).
         ' -o '.escapeshellarg($wordFile).
         ' --from html+tex_math_dollars+tex_math_single_backslash '.
         ' --standalone '.
         ' -V mainfont="Times New Roman" '.
         ' -V fontsize=12pt '.
         ' -V papersize=a4 '.
         ' -V geometry:margin=2cm';

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
}
