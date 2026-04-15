@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6 md:px-6 lg:px-8 max-w-5xl">

        {{-- BẢNG CẢNH BÁO MÀU ĐỎ (Tùy chọn, bác có thể giữ hoặc bỏ tùy ý) --}}
        <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl flex items-start gap-3">
            <div class="p-2 bg-rose-100 text-rose-600 rounded-full shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-rose-800 font-bold text-base mb-1">Cảnh báo thao tác xóa</h3>
                <p class="text-rose-600 text-sm">Bạn đang yêu cầu xóa vĩnh viễn câu hỏi này. <strong>Không thể hoàn
                        tác!</strong></p>
            </div>
        </div>

        {{-- HEADER & 2 NÚT THAO TÁC GÓC TRÊN BÊN PHẢI --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    Câu hỏi: <span class="ml-2 text-blue-600 format-katex">{{ $question->tag_name }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    @if (!empty($question->shared_context_id))
                        <a href="#"
                            class="text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">Câu hỏi có dữ
                            liệu dùng chung</a> |
                    @endif
                    Loại: {{ $question->questionType->name ?? 'N/A' }} | Ngày tạo:
                    {{ $question->created_at->format('d/m/Y') }}
                </p>
            </div>

            {{-- KHU VỰC 2 NÚT BẤM --}}
            <div class="flex items-center gap-3">
                {{-- 1. Nút Quay về --}}
                <button onclick="window.history.back();" type="button"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Quay về
                </button>

                {{-- 2. Nút Xóa --}}
                <form action="{{ route('questions.destroy', $question->id) }}" method="POST" class="inline-block m-0"
                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn câu hỏi này không?');">
                    @csrf
                    @method('DELETE')

                    @if (isset($fromContextId))
                        <input type="hidden" name="shared_context_id" value="{{ $fromContextId }}">
                    @endif

                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-1">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        Xóa câu hỏi này
                    </button>
                </form>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- BẮT ĐẦU PHẦN NỘI DUNG Y HỆT SHOW.BLADE.PHP (Đã xóa các nút ở dưới) --}}
        {{-- ================================================================= --}}

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="col-span-1 md:col-span-2 space-y-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Thông tin thuộc tính</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-gray-500 mb-1">Trạng thái</span>
                            @if ($question->status == 1)
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-medium border border-green-200"><i
                                        class="fa-solid fa-check-circle mr-1"></i> Đã thẩm định</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-amber-100 text-amber-700 rounded-md text-xs font-medium border border-amber-200"><i
                                        class="fa-solid fa-clock mr-1"></i> Chờ thẩm định</span>
                            @endif
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 mb-1">Mức độ nhận thức</span>
                            <span
                                class="text-sm font-medium text-gray-800">{{ $question->cognitiveLevel->name ?? 'Chưa xác định' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-gray-500 mb-1">Độ khó (tự đánh giá)</span>
                            <span
                                class="text-sm font-medium text-gray-800">{{ $question->difficulty_index ?? 'Chưa đánh giá' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-1">
                <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100 shadow-sm h-full">
                    <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-wider mb-3">Mục tiêu đánh giá (YCCĐ)
                    </h3>
                    @if ($question->objectives && $question->objectives->isNotEmpty())
                        <ul class="space-y-2">
                            @foreach ($question->objectives as $obj)
                                <li class="text-sm text-gray-700 bg-white p-2 rounded border border-indigo-50 shadow-sm">
                                    <strong class="text-indigo-700">{{ $obj->tag_name }}:</strong> {!! $obj->description !!}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 italic">Câu hỏi chưa được gắn mục tiêu đánh giá nào.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    Nội dung câu hỏi
                </h3>

                <div class="prose max-w-none text-gray-800 mb-8 format-katex text-base leading-relaxed">
                    {!! $question->stem !!}
                </div>

                {{-- Hiển thị đáp án nếu có --}}
                @if ($question->choices && $question->choices->isNotEmpty())
                    <div class="mt-6">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">Các lựa chọn / Đáp án</h4>
                        <div class="space-y-3">
                            @foreach ($question->choices as $index => $choice)
                                @php
                                    $isCorrect = $choice->is_correct == 1;
                                    $bgColor = $isCorrect
                                        ? 'bg-green-50 border-green-200'
                                        : 'bg-gray-50 border-gray-200';
                                    $textColor = $isCorrect ? 'text-green-800' : 'text-gray-700';
                                    $iconColor = $isCorrect ? 'text-green-500' : 'text-gray-400';
                                    $iconPath = $isCorrect ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12';
                                    $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                                    $label = $letters[$index] ?? '?';

                                    // Bắt tất cả các kiểu tên cột nội dung đáp án
                                    $choiceContent =
                                        $choice->choice_text ??
                                        ($choice->content ?? ($choice->text ?? ($choice->description ?? '')));
                                @endphp
                                <div class="flex items-start p-3 rounded-lg border {{ $bgColor }}">
                                    <div class="flex-shrink-0 mt-0.5 mr-3">
                                        <span
                                            class="flex items-center justify-center w-6 h-6 rounded-full border {{ $isCorrect ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-300 bg-white text-gray-500' }} text-xs font-bold">
                                            {{ $label }}
                                        </span>
                                    </div>
                                    <div class="flex-1 {{ $textColor }} format-katex text-sm pt-0.5">
                                        {!! $choiceContent !!}
                                    </div>
                                    <div class="flex-shrink-0 ml-3">
                                        <svg class="w-5 h-5 {{ $iconColor }}" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $iconPath }}"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Hiển thị lời giải nếu có --}}
            @if ($question->explanation)
                <div class="bg-gray-50 border-t border-gray-200 p-6">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lời giải chi tiết / Hướng dẫn chấm
                    </h3>
                    <div
                        class="prose max-w-none text-gray-700 text-sm format-katex bg-white p-4 rounded-lg border border-gray-200">
                        {!! $question->explanation->content !!}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
