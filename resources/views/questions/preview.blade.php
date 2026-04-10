@extends('layouts.main')

@section('content')
    <div class="max-w-6xl mx-auto py-8 px-4">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <i class="fa-solid fa-file-import text-blue-500"></i> Xem trước dữ liệu câu hỏi
                </h2>
                <p class="text-slate-500 mt-1">Vui lòng kiểm tra lại cấu trúc và nội dung câu hỏi trước khi lưu vào hệ thống.
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('questions.index') }}"
                    class="px-5 py-2.5 bg-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-300 transition">
                    Hủy bỏ
                </a>
                <form action="{{ route('questions.upload.import') }}" method="POST">
                    @csrf
                    <input type="hidden" name="import_uuid" value="{{ $importUuid }}">

                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition">
                        <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Xác nhận Lưu
                    </button>
                </form>
            </div>
        </div>

        {{-- Danh sách câu hỏi --}}
        <div class="space-y-8">
            @foreach ($structuredData as $index => $item)
                @if ($item['type'] === 'SharedContext')
                    {{-- KHỐI CÂU HỎI CÓ DỮ LIỆU DÙNG CHUNG --}}
                    <div class="bg-white border-2 border-indigo-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-center flex-wrap gap-2 mb-4">
                                <span class="bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-bold">
                                    Câu hỏi có dữ liệu dùng chung
                                </span>
                                <i class="fa-solid fa-layer-group text-indigo-500 mr-2"></i>
                                
                                {{-- TRẠNG THÁI --}}
                                @if ($item['is_ready_to_save'] ?? false)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full font-bold">
                                        <i class="fa-solid fa-check-circle mr-1"></i> Dữ liệu hợp lệ
                                    </span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 text-xs px-3 py-1 rounded-full font-bold">
                                        <i class="fa-solid fa-xmark-circle mr-1"></i> Dữ liệu bị lỗi
                                    </span>
                                @endif
                            </div>

                            {{-- HIỂN THỊ LỖI & CẢNH BÁO --}}
                            @if (!empty($item['errors']) || !empty($item['warnings']))
                                <div class="mb-5 space-y-3">
                                    @if (!empty($item['errors']))
                                        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg">
                                            <div class="flex items-center text-rose-800 font-bold mb-2">
                                                <i class="fa-solid fa-triangle-exclamation mr-2"></i> Lỗi dữ liệu chung:
                                            </div>
                                            <ul class="list-disc list-inside text-sm text-rose-700 space-y-1">
                                                @foreach ($item['errors'] as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if (!empty($item['warnings']))
                                        <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-lg">
                                            <div class="flex items-center text-amber-800 font-bold mb-2">
                                                <i class="fa-solid fa-circle-exclamation mr-2"></i> Cảnh báo dữ liệu chung:
                                            </div>
                                            <ul class="list-disc list-inside text-sm text-amber-700 space-y-1">
                                                @foreach ($item['warnings'] as $warning)
                                                    <li>{{ $warning }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- KHUNG NỘI DUNG VÀ GHI CHÚ --}}
                            <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 prose max-w-none format-katex">
                                <p class="text-sm font-semibold text-slate-500 mb-2 uppercase tracking-wider">
                                    Nội dung dùng chung
                                </p>
                                {!! $item['context_data']['content'] !!}

                                {{-- PHẦN NOTE MỚI THÊM --}}
                                @if(!empty($item['context_data']['note']))
                                    <div class="mt-4 pt-4 border-t border-slate-200 text-sm text-slate-600">
                                        <div class="flex items-center gap-2 font-semibold text-indigo-700 mb-1">
                                            <i class="fa-solid fa-note-sticky"></i> Ghi chú/Hướng dẫn:
                                        </div>
                                        <div class="italic">
                                            {{ $item['context_data']['note'] }}
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Danh sách câu hỏi con --}}
                            <div class="mt-6 pl-8 border-l-4 border-indigo-200 space-y-6">
                                @foreach ($item['questions'] as $qIndex => $q)
                                    @include('questions.partials._preview_question', [
                                        'q' => $q,
                                        'index' => $qIndex + 1,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                @elseif($item['type'] === 'IndependentQuestion')
                    {{-- KHỐI CÂU HỎI ĐỘC LẬP (Không có note) --}}
                    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full font-bold">
                                Câu hỏi độc lập
                            </span>
                        </div>
                        @include('questions.partials._preview_question', [
                            'q' => $item['question_data'],
                            'index' => $index + 1,
                        ])
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection