<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PdfService
{
    public function generateFromHtml(string $html)
    {
        // 1. Tạo thư mục tạm nếu chưa có
        $tempDir = storage_path('app/temp_pdf');
        if (! File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // 2. Tạo tên file ngẫu nhiên để tránh trùng lặp
        $fileName = Str::random(10);
        $htmlFile = $tempDir.'/'.$fileName.'.html';
        $pdfFile = $tempDir.'/'.$fileName.'.pdf';

        // 3. Xử lý đường dẫn ảnh: Pandoc trên Mac cần đường dẫn tuyệt đối trong ổ đĩa
        // Chuyển http://localhost:8000/storage thành /Users/.../public/storage
        //$html = str_replace(asset('storage'), public_path('storage'), $html);
        $html = make_image_paths_local($html);

        // Lưu file HTML tạm
        File::put($htmlFile, $html);

        // 4. Chạy lệnh Pandoc
        // --pdf-engine=xelatex: Hỗ trợ tiếng Việt và Font hệ thống tốt nhất
        // -V mainfont="Times New Roman": Cấu hình font cho đúng chuẩn
        // $command = 'pandoc '.escapeshellarg($htmlFile).
        //    ' -o '.escapeshellarg($pdfFile).
        //    ' --from html+tex_math_dollars+tex_math_single_backslash '.
        //    ' --pdf-engine=xelatex '.
        //    ' -V mainfont="Times New Roman" '.
        //    ' -V geometry:margin=2cm';

        $command = 'pandoc '.escapeshellarg($htmlFile).
         ' -o '.escapeshellarg($pdfFile).
         ' --from html+tex_math_dollars+tex_math_single_backslash '.
         ' --pdf-engine=xelatex '.
         ' -V mainfont="Times New Roman" '.
         ' -V papersize=a4 '.
         ' -V geometry:"top=2cm, bottom=2cm, left=2cm, right=2cm" '.
         ' --columns=80'; // Giúp Pandoc tính toán ngắt dòng trong bảng tốt hơn

        shell_exec($command);

        // 5. Kiểm tra và trả file về cho Controller, sau đó xóa file tạm
        if (File::exists($pdfFile)) {
            $content = File::get($pdfFile);

            // Xóa rác sau khi in xong
            File::delete([$htmlFile, $pdfFile]);

            return $content;
        }

        throw new \Exception('Pandoc không thể tạo được file PDF. Kiểm tra lại nội dung HTML hoặc cấu hình xelatex.');
    }
}
