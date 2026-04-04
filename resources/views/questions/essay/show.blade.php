@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-5xl">

        {{-- THANH ĐIỀU HƯỚNG TRÊN CÙNG --}}
        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('questions.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay về
            </a>
            <a href="#"
                class="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl font-medium hover:bg-amber-600 transition shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i> Chỉnh sửa
            </a>
        </div>

        {{-- KHUNG NỘI DUNG CHÍNH --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

            {{-- Hàng 1: Title (Chữ đậm, to hơn, bẻ dòng nếu UUID quá dài) --}}
            <h1 class="text-xl md:text-2xl font-bold text-slate-800 break-all mb-4">
                Chi tiết câu hỏi: <span class="text-blue-600">{{ $question->tag_name }}</span>
            </h1>

            {{-- Hàng 2: Nội dung chính --}}
            <div class="text-sm font-bold text-slate-800 mb-2">
                Nội dung chính: <span class="text-slate-600">{{ $question->name }}</span>
            </div>

            {{-- Hàng 3: Loại câu hỏi & Mức độ nhận thức --}}
            <div class="text-sm font-bold text-slate-800 mb-4 flex flex-wrap gap-x-8 gap-y-2">
                <div>
                    Loại câu hỏi: <span
                        class="text-slate-600">{{ $question->questionType->name ?? 'Không xác định' }}</span>
                </div>
                <div>
                    Mức độ nhận thức: <span
                        class="text-slate-600">{{ $question->cognitiveLevel->name ?? 'Không xác định' }}</span>
                </div>
            </div>

            {{-- Hàng 4: Các yêu cầu cần đạt (Chữ nhỏ, không đậm, có dấu tick, có quét katex) --}}
            @if ($question->objectives && $question->objectives->count() > 0)
                <div class="mb-6">
                    <div class="text-sm text-slate-800 font-bold mb-2">Các yêu cầu cần đạt:</div>
                    <ul class="text-sm text-slate-600 space-y-1">
                        @foreach ($question->objectives as $objective)
                            {{-- Thay 'katex-scan' bằng class thực tế bạn dùng để gọi KaTeX --}}
                            <li class="flex items-start gap-2 katex-scan">
                                <i class="fa-solid fa-check text-green-500 mt-1"></i>
                                <span>{!! $objective->name ?? ($objective->description ?? 'Mục tiêu ' . $objective->id) !!}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Hàng 5: Nội dung câu hỏi (Stem - Quét KaTeX, render HTML) --}}
            <div class="mt-8 mb-6">
                <h3 class="text-base font-bold text-slate-800 mb-3 border-b border-slate-100 pb-2">
                    Nội dung câu hỏi:
                </h3>
                <div class="text-base text-slate-800 leading-relaxed katex-scan">
                    {!! make_image_paths_absolute($question->stem) !!}
                </div>
            </div>

            {{-- Hàng 6: Lời giải (Explanation) --}}
            <div class="mb-6">
                <h3 class="text-base font-bold text-slate-800 mb-3 border-b border-slate-100 pb-2">
                    Lời giải:
                </h3>
                <div
                    class="text-base text-slate-800 leading-relaxed katex-scan bg-slate-50 p-4 rounded-xl border border-slate-100">
                    @if ($question->explanation && !empty($question->explanation->content))
                        {!! make_image_paths_absolute($question->explanation->content) !!}
                    @else
                        <span class="text-slate-400 italic">Chưa có lời giải.</span>
                    @endif
                </div>
            </div>

            {{-- Hàng 7: Cảnh báo Choices (Nếu lỡ có dữ liệu rác của trắc nghiệm) --}}
            @if ($question->choices && $question->choices->count() > 0)
                <div class="mb-8 p-5 bg-rose-50 border border-rose-200 rounded-xl">
                    <div class="text-rose-600 font-bold flex items-center gap-2 mb-3">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Dữ liệu sai định dạng - yêu cầu viết lại câu hỏi
                    </div>
                    <ul class="space-y-2 text-rose-700 katex-scan">
                        @foreach ($question->choices as $choice)
                            <li>• {!! $choice->content ?? 'Lựa chọn ' . $choice->id !!}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Hàng 8: Các thông số khác (Trạng thái, Độ khó, Thống kê) --}}
            <div
                class="mt-8 pt-6 border-t border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6 bg-slate-50/50 -mx-8 -mb-8 p-8 rounded-b-2xl">

                {{-- Trạng thái --}}
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Trạng thái</span>
                    @if ($question->status == 1 || $question->status == 'active')
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-bold">
                            <i class="fa-solid fa-circle-check"></i> Đã thẩm định
                        </span>
                    @elseif($question->status == 2)
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-sm font-bold">
                            <i class="fa-solid fa-rotate-right"></i> Cần soạn lại
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-sm font-bold">
                            <i class="fa-solid fa-clock"></i> Chưa thẩm định
                        </span>
                    @endif
                </div>

                {{-- Độ khó --}}
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Độ khó</span>
                    <span
                        class="inline-flex items-center justify-center min-w-[3rem] px-3 py-1 bg-white text-slate-700 rounded-lg text-sm font-bold border border-slate-200">
                        {{ $question->difficulty_index ?? '0' }}
                    </span>
                </div>

                {{-- Thống kê --}}
                <div>
                    <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Thống kê sử
                        dụng</span>
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 bg-white text-blue-600 rounded-lg text-sm font-bold border border-slate-200">
                        <i class="fa-solid fa-chart-pie"></i>
                        {{ $question->statistic->used_count ?? 0 }} lần xuất đề
                    </span>
                </div>

            </div>
        </div>
        {{-- CÁC NÚT CHỨC NĂNG MỞ RỘNG (THÊM MỚI VÀO ĐÂY) --}}
        <div class="mt-6 flex flex-wrap items-center justify-end gap-3">
            <a href="{{ route('questions.es.printWord', $question->id) }}"
                class="flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium shadow-sm transition-all"
                title="Tải về tệp Word (.docx)">
                <i class="fa-solid fa-file-word text-lg"></i> Xuất ra Word
            </a>

            <a href="{{ route('questions.es.printPdf', $question->id) }}"
                class="flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-medium shadow-sm transition-all"
                title="Tải về tệp PDF">
                <i class="fa-solid fa-file-pdf text-lg"></i> Xuất ra PDF
            </a>
        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Hàm init KaTeX
            const renderKatex = () => {
                if (typeof renderMathInElement !== 'undefined') {
                    // Quét tất cả các thẻ có class 'katex-scan' mà ta đã đặt ở trên
                    const mathElements = document.querySelectorAll('.katex-scan');
                    mathElements.forEach(function(el) {
                        renderMathInElement(el, {
                            delimiters: [{
                                    left: "$$",
                                    right: "$$",
                                    display: true
                                },
                                {
                                    left: "$",
                                    right: "$",
                                    display: false
                                }, // Dòng này cực kỳ quan trọng: Bật nhận diện dấu $
                                {
                                    left: "\\(",
                                    right: "\\)",
                                    display: false
                                },
                                {
                                    left: "\\[",
                                    right: "\\]",
                                    display: true
                                }
                            ],
                            throwOnError: false, // Bỏ qua lỗi nếu gõ sai công thức (không làm sập trang)
                            output: 'htmlAndMathml'
                        });
                    });
                } else {
                    // Nếu thư viện chưa tải xong thì thử lại sau 100ms
                    setTimeout(renderKatex, 100);
                }
            };

            // Gọi hàm
            renderKatex();
        });
    </script>
@endsection
