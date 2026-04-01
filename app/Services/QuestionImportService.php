<?php

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\QuestionHandlers\QuestionHandlerFactory;

class QuestionImportService
{
    /**
     * Hàm chính để xử lý file Word tải lên.
     *
     * @param UploadedFile $file File Word (.docx)
     * @param array $commonData Dữ liệu dùng chung (VD: topic_id, cognitive_level_id...)
     * @return array Kết quả import (số câu thành công, danh sách lỗi)
     */
    public function importFromDocx(UploadedFile $file, array $commonData = []): array
    {
        $importedCount = 0;
        $errors = [];

        // 1. Tạo thư mục tạm thời để xử lý file
        $tempDir = 'imports/temp_' . time();
        Storage::disk('local')->makeDirectory($tempDir);

        try {
            // Lưu file gốc vào thư mục tạm
            $filePath = $file->storeAs($tempDir, 'upload.docx', 'local');
            $fullFilePath = Storage::disk('local')->path($filePath);
            $outputHtmlPath = Storage::disk('local')->path($tempDir . '/output.html');
            $mediaDir = Storage::disk('local')->path($tempDir . '/media');

            // 2. Gọi Pandoc convert Docx -> HTML (Bóc tách ảnh và Toán học)
            $this->runPandoc($fullFilePath, $outputHtmlPath, $mediaDir);

            // 3. Đọc nội dung HTML đã convert
            $htmlContent = file_get_contents($outputHtmlPath);

            // 4. Băm HTML thành mảng các câu hỏi thô (Raw Questions)
            $rawQuestions = $this->parseHtmlToRawQuestions($htmlContent);

            // 5. Duyệt qua từng câu để bóc tách và lưu vào DB
            foreach ($rawQuestions as $index => $raw) {
                try {
                    // Phân tích loại câu hỏi (Trắc nghiệm hay Tự luận)
                    $typeId = $this->detectQuestionType($raw);

                    // Bóc tách dữ liệu chi tiết dựa theo loại
                    $questionData = $this->extractQuestionData($raw, $typeId);

                    // Gộp với dữ liệu chung (do giáo viên chọn trên form upload)
                    $finalData = array_merge($commonData, $questionData);

                    // Gọi Handler tương ứng để lưu Database
                    $handler = QuestionHandlerFactory::make($typeId);
                    $handler->store($finalData);

                    $importedCount++;
                } catch (Exception $e) {
                    // Ghi nhận lỗi nhưng không làm chết vòng lặp (để import tiếp câu khác)
                    $errors[] = "Lỗi ở câu " . ($index + 1) . ": " . $e->getMessage();
                }
            }

        } catch (Exception $e) {
            Log::error('Lỗi Import Pandoc: ' . $e->getMessage());
            $errors[] = "Lỗi hệ thống khi xử lý file: " . $e->getMessage();
        } finally {
            // 6. Dọn dẹp rác: Xóa thư mục tạm sau khi làm xong
            Storage::disk('local')->deleteDirectory($tempDir);
        }

        return [
            'success_count' => $importedCount,
            'errors'        => $errors
        ];
    }

    /**
     * Chạy lệnh Pandoc thông qua Symfony Process
     */
    private function runPandoc(string $inputPath, string $outputPath, string $mediaDir): void
    {
        // Câu lệnh mẫu: pandoc input.docx -o output.html --extract-media=media_dir --mathml
        $process = new Process([
            'pandoc',
            $inputPath,
            '-o', $outputPath,
            '--extract-media=' . $mediaDir,
            '--mathml' // Hoặc '--mathjax' tùy thuộc vào cách bạn muốn render công thức
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Dùng Regex để cắt chuỗi HTML dài thành mảng các câu hỏi riêng biệt.
     * Cần quy định dấu hiệu nhận biết, ví dụ: "Câu 1:", "Câu 2:"
     */
    private function parseHtmlToRawQuestions(string $htmlContent): array
    {
        // TODO: Viết logic Regex cắt chuỗi. 
        // Gợi ý: Tìm các đoạn bắt đầu bằng "Câu \d+:" đến trước "Câu \d+:" tiếp theo.
        return [];
    }

    /**
     * Dựa vào nội dung câu thô để đoán xem nó là Trắc nghiệm hay Tự luận.
     * Trả về question_type_id tương ứng trong Database.
     */
    private function detectQuestionType(string $rawQuestion): int
    {
        // TODO: Kiểm tra nếu có A., B., C., D. -> Trắc nghiệm (VD: ID = 1)
        // Nếu không có -> Tự luận (VD: ID = 2)
        return 1; 
    }

    /**
     * Bóc tách các thành phần (Lời dẫn, đáp án, lời giải) từ chuỗi HTML thô của 1 câu
     */
    private function extractQuestionData(string $rawQuestion, int $typeId): array
    {
        // TODO: Viết Regex bóc tách chi tiết.
        // Cần trả về mảng theo cấu trúc mà validateData() của Handler yêu cầu.
        
        /* Cấu trúc mẫu trả về:
        return [
            'stem' => '<p>Động năng là gì?</p>',
            'choices' => [ ... ], // Nếu là trắc nghiệm
            'explanation' => '<p>Là năng lượng do chuyển động...</p>'
        ];
        */
        
        return [];
    }
}