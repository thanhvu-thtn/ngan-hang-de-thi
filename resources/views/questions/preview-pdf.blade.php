<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Preview Câu Hỏi</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #333;
            line-height: 1.25;
            /* Giữ nguyên khoảng cách dòng tiêu chuẩn */
        }

        /* 1. XÓA BỎ MARGIN MẶC ĐỊNH CỦA THẺ <p> DO EDITOR SINH RA */
        p {
            margin: 0;
            padding: 0;
        }

        .stem {
            /* 2. THU HẸP KHOẢNG CÁCH GIỮA PHẦN DẪN VÀ CÁC LỰA CHỌN */
            margin-bottom: 6px;
            text-align: justify;
            float: left;
            /* Đẩy span sang trái */
            margin-right: 5px;
        }

        .stem p:first-child {
            display: inline;
            /* Biến thẻ p đầu tiên thành dạng văn bản thông thường */
        }

        .choices-wrapper {
            display: grid;
            /* 3. TÁCH RIÊNG GAP CHO HÀNG VÀ CỘT */
            row-gap: 6px;
            /* Khoảng cách giữa các hàng dọc thu nhỏ lại (gần bằng dòng bình thường) */
            column-gap: 20px;
            /* Khoảng cách giữa các cột ngang giữ cho rộng rãi để A,B,C,D không dính nhau */
            width: 100%;
        }

        /* 1 hàng 4 lựa chọn */
        .grid-1x4 {
            grid-template-columns: repeat(4, 1fr);
        }

        /* 2 hàng, mỗi hàng 2 lựa chọn */
        .grid-2x2 {
            grid-template-columns: repeat(2, 1fr);
        }

        /* Mặc định 1 hàng 1 lựa chọn */
        .grid-1x1 {
            grid-template-columns: 1fr;
        }

        .choice-item {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .choice-label {
            font-weight: bold;
        }

        img {
            max-width: 100%;
            height: auto;
        }
    </style>

    {{-- NẾU CÂU HỎI CỦA BÁC CÓ TOÁN HỌC (KaTeX), BÁC MỞ COMMENT ĐOẠN DƯỚI ĐỂ NHÚNG KATEX VÀO NHÉ --}}
</head>

<body>

    {{-- PHẦN DẪN (STEM) - Luôn luôn in --}}
    @php
        $stem = $question->stem;
        $label = '<span style="font-weight: 600;">Câu 1. </span>';

        // Kiểm tra nếu chuỗi bắt đầu bằng <p>
        if (str_starts_with(trim($stem), '<p>')) {
            // Thay thế thẻ <p> đầu tiên bằng <p> kèm nhãn
            $stem = preg_replace('/<p>/', '<p>' . $label, $stem, 1);
        } else {
            // Nếu không có <p>, có thể bạn muốn thêm nhãn vào phía trước luôn
            $stem = $label . $stem;
        }
    @endphp

    <div class="stem">
        {!! $stem !!}
    </div>

    {{-- PHẦN LỰA CHỌN (Chỉ in nếu là Multiple Choice) --}}
    @if (isset($question->questionType) && $question->questionType->code === 'MC')
        @php
            // Lấy 3 ký tự đầu của layout code, nếu null thì mặc định là '1x1'
            $layoutCode = $question->layout ? substr($question->layout->code, 0, 3) : '1x1';

            // Khởi tạo class cho Grid
            $gridClass = 'grid-1x1';
            if ($layoutCode === '1x4') {
                $gridClass = 'grid-1x4';
            } elseif ($layoutCode === '2x2') {
                $gridClass = 'grid-2x2';
            }

            // Nhãn A, B, C, D
            $labels = ['A', 'B', 'C', 'D', 'E', 'F'];
        @endphp

        <div class="choices-wrapper {{ $gridClass }}">
            @foreach ($question->choices as $index => $choice)
                <div class="choice-item">
                    <span class="choice-label">{{ $labels[$index] ?? '' }}.</span>
                    <div class="choice-content">{!! $choice->content !!}</div>
                </div>
            @endforeach
        </div>
    @endif

</body>

</html>
