@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6 md:px-6 lg:px-8 max-w-5xl">

        <div class="flex items-center justify-between pb-4 border-b border-gray-200 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                    Câu hỏi: <span class="ml-2 text-blue-600 format-katex">{{ $data['tag_name'] }}</span>
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{-- Kiểm tra nếu có dữ liệu dùng chung --}}
                    @if (!empty($data['shared_context_id']))
                        <a href="#" class="text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">Câu
                            hỏi có dữ liệu dùng chung</a> |
                    @endif
                    Loại: {{ $data['type_name'] }} | Ngày tạo: {{ $data['created_at'] }} | Thứ tự: {{ $data['sort_order'] ?? 0 }}
                </p>
            </div>
            <button onclick="window.history.back();"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm cursor-pointer">
                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Quay lại
            </button>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        1. Thông tin câu hỏi
                    </h2>
                </div>
                <div class="p-6 space-y-5">

                    @if (!empty($data['objectives']))
                        <div>
                            <span class="block text-sm font-semibold text-gray-700 mb-2 uppercase tracking-wide">Yêu cầu cần
                                đạt:</span>
                            <div class="space-y-3 border-l-2 border-orange-300 pl-4">
                                @foreach ($data['objectives'] as $obj)
                                    <div class="text-gray-800">
                                        <span
                                            class="inline-block bg-orange-100 text-orange-800 text-xs font-bold px-2 py-0.5 rounded mr-2 mb-1">
                                            {{ $obj['tag_name'] }}
                                        </span>
                                        <div class="inline format-katex prose prose-sm max-w-none">
                                            {!! $obj['description'] !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <span class="inline-block text-sm font-semibold text-gray-700 mr-2 uppercase tracking-wide">Tóm tắt
                            nội dung:</span>
                        <span class="text-gray-800 format-katex">{{ $data['name'] }}</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-8 gap-y-4 pt-3 border-t border-gray-100">
                        <div class="flex items-center">
                            <span class="text-sm font-semibold text-gray-700 mr-2">Mức độ nhận thức:</span>
                            <span
                                class="px-3 py-1 bg-blue-50 text-blue-700 text-sm font-medium rounded-full border border-blue-100">{{ $data['cognitive_level'] }}</span>
                        </div>

                        <div class="flex items-center">
                            <span class="text-sm font-semibold text-gray-700 mr-2">Trạng thái:</span>
                            @if ($data['status'] == 0)
                                <span
                                    class="px-3 py-1 bg-yellow-50 text-yellow-700 text-sm font-medium rounded-full border border-yellow-200">Chờ
                                    duyệt</span>
                            @elseif($data['status'] == 1)
                                <span
                                    class="px-3 py-1 bg-green-50 text-green-700 text-sm font-medium rounded-full border border-green-200">Đã
                                    duyệt</span>
                            @else
                                <span
                                    class="px-3 py-1 bg-red-50 text-red-700 text-sm font-medium rounded-full border border-red-200">Bị
                                    từ chối</span>
                            @endif
                        </div>

                        @if (!empty($data['layout_name']))
                            <div class="flex items-center">
                                <span class="text-sm font-semibold text-gray-700 mr-2">Bố cục in:</span>
                                <span
                                    class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-medium rounded-full border border-gray-200">{{ $data['layout_name'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-3 flex justify-between items-center">
                    <h2 class="text-base font-semibold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        2. Nội dung câu hỏi
                    </h2>
                    <button type="button"
                        class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 text-sm font-medium rounded-md transition-colors duration-150 focus:outline-none">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Preview
                    </button>
                </div>
                <div class="p-6">

                    <div class="prose prose-blue max-w-none text-gray-800 format-katex mb-6">
                        {!! $data['stem'] !!}
                    </div>

                    <div class="mt-4">
                        @if ($data['type_code'] === 'MC' && !empty($data['choices']))
                            <div class="space-y-3">
                                @foreach ($data['choices'] as $index => $choice)
                                    @php
                                        $label = chr(65 + $index); // A, B, C, D
                                    @endphp
                                    <div
                                        class="flex items-stretch w-full rounded-lg border {{ $choice['is_correct'] ? 'bg-green-50/50 border-green-300' : 'bg-gray-50 border-gray-200' }}">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-12 border-r {{ $choice['is_correct'] ? 'border-green-300 font-bold text-green-700' : 'border-gray-200 font-medium text-gray-600' }}">
                                            {{ $label }}
                                        </div>

                                        <div class="flex-grow p-3 format-katex prose prose-sm max-w-none text-gray-800">
                                            {!! $choice['content'] !!}
                                        </div>

                                        <div
                                            class="flex-shrink-0 flex items-center px-4 space-x-3 border-l {{ $choice['is_correct'] ? 'border-green-300' : 'border-gray-200' }}">
                                            @if (isset($choice['ratio']))
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-200 text-gray-700"
                                                    title="Tỉ lệ điểm/trọng số">
                                                    Ratio: {{ $choice['ratio'] }}
                                                </span>
                                            @endif

                                            @if ($choice['is_correct'])
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @else
                                                <div class="w-6 h-6"></div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif(in_array($data['type_code'], ['TF', 'SA']))
                            <div
                                class="flex items-center p-4 rounded-lg bg-green-50 border border-green-200 text-green-900">
                                <span class="font-semibold mr-2">Đáp án:</span>
                                <span class="format-katex">{!! $data['answer'] !!}</span>
                            </div>
                        @elseif($data['type_code'] === 'ES')
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-200 text-gray-500 italic">
                                {{-- Câu hỏi tự luận. Vui lòng xem hướng dẫn chấm bên dưới. --}}
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            @if (!empty($data['explanation']))
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-6 py-3">
                        <h2 class="text-base font-semibold text-gray-800 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                </path>
                            </svg>
                            3. Lời giải chi tiết / Hướng dẫn chấm
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="prose prose-purple max-w-none text-gray-800 format-katex">
                            {!! $data['explanation'] !!}
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-end space-x-3 pt-4">
                <button type="button"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Xuất Word
                </button>

                <button type="button"
                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                        </path>
                    </svg>
                    Xuất PDF
                </button>
            </div>

        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof window.renderKaTeX === 'function') {
                    window.renderKaTeX();
                }
            });
        </script>
    </div>
@endsection
