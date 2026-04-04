<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Export PDF</title>

    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            background: white;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border: none !important;
            /* Ép buộc xóa viền bao ngoài bảng */
        }

        table,
        th,
        td {
            border: none !important;
            /* Ép buộc xóa viền của các ô */
            padding: 5px;
        }
    </style>

    <link rel="stylesheet" href="{{ public_path('vendor/katex/katex.min.css') }}">
    <script defer src="{{ public_path('vendor/katex/katex.min.js') }}"></script>
    <script defer src="{{ public_path('vendor/katex/contrib/auto-render.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            renderMathInElement(document.body, {
                delimiters: [{
                        left: '$$',
                        right: '$$',
                        display: true
                    },
                    {
                        left: '\\[',
                        right: '\\]',
                        display: true
                    },
                    {
                        left: '$',
                        right: '$',
                        display: false
                    },
                    {
                        left: '\\(',
                        right: '\\)',
                        display: false
                    }
                ],
                throwOnError: false
            });
        });
    </script>
</head>

<body>

    <h3>Nội dung câu hỏi:</h3>
    <div class="katex-scan">
        {{-- Dùng str_replace để ép ảnh tải thẳng từ ổ cứng vật lý, tránh kẹt mạng 100% --}}
        {!! str_replace(url('/'), public_path(), $question->stem) !!}
    </div>

    {{-- Hàng 6: Lời giải (Explanation) --}}
    <h3>Lời giải:</h3>
    <div class="katex-scan">
        @if ($question->explanation && !empty($question->explanation->content))
            {{-- Áp dụng tương tự cho phần lời giải --}}
            {!! str_replace(url('/'), public_path(), $question->explanation->content) !!}
        @else
            <span>Chưa có lời giải.</span>
        @endif
    </div>

</body>

</html>
