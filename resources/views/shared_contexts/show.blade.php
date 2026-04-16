@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-5xl">
        {{-- PHẦN 1: THÔNG TIN SHARED CONTEXT --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
                <div>
                    <span
                        class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded uppercase mb-2 inline-block">
                        Mã định danh: {{ $context->tag_name }}
                    </span>
                    <h2 class="text-xl font-bold text-slate-800">Nội dung dùng chung</h2>
                    @if ($context->note)
                        <p class="text-sm text-slate-500 mt-1 italic">
                            <i class="fa-solid fa-note-sticky mr-1"></i> Ghi chú: {{ $context->note }}
                        </p>
                    @endif
                </div>

                {{-- Nút Quay về danh sách --}}
                <a href="{{ route('shared-contexts.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-bold rounded-xl transition-all shadow-sm gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Quay về
                </a>
            </div>

            {{-- Đã thêm class format-katex --}}
            <div class="p-6 prose max-w-none text-slate-700 leading-relaxed min-h-[100px] format-katex">
                {!! $context->content !!}
            </div>
            {{-- === DÁN ĐOẠN NÀY VÀO ĐÂY === --}}
            {{-- Thanh công cụ dưới ô Nội dung --}}
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
                {{-- Trái: Nút Preview --}}
                <a href="#"
                    class="inline-flex items-center px-4 py-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 font-semibold rounded-lg transition-colors gap-2 text-sm">
                    <i class="fa-solid fa-eye"></i> Preview
                </a>

                {{-- Phải: Nút Thêm câu hỏi mới --}}
                <a href="{{ route('questions.create', ['shared_context_id' => $context->id]) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-md gap-2">
                    <i class="fa-solid fa-plus"></i> Thêm câu hỏi mới
                </a>
            </div>
            {{-- === KẾT THÚC ĐOẠN DÁN === --}}
        </div>

        {{-- PHẦN 2: DANH SÁCH CÂU HỎI --}}
        <div class="flex items-center gap-3 mb-4">
            <h3 class="text-lg font-bold text-slate-800">Các câu hỏi dùng chung dữ liệu trên
                ({{ $context->questions->count() }})</h3>
            <div class="flex-1 h-px bg-slate-200"></div>
        </div>

        <div class="space-y-6">
            @forelse($context->questions as $index => $question)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden group">
                    {{-- a. Thanh công cụ (Hàng 3 nút lệnh & Preview) --}}
                    <div class="bg-slate-50 px-4 py-2 border-b border-slate-200 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-500 mr-2">#{{ $index + 1 }}</span>
                            <a href="{{ route('questions.show', $question->id) }}"
                                class="p-1.5 text-slate-600 hover:text-blue-600 transition-colors" title="Xem chi tiết">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('questions.edit', $question->id) }}"
                                class="p-1.5 text-slate-600 hover:text-amber-600 transition-colors" title="Sửa">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <a href="{{ route('questions.delete', ['id' => $question->id, 'shared_context_id' => $context->id]) }}"
                                class="text-red-500 hover:text-red-700">
                                <i class="fa-solid fa-trash"></i> Xóa
                            </a>
                        </div>

                        <a href="#" class="text-xs font-bold text-blue-600 hover:underline flex items-center gap-1">
                            <i class="fa-solid fa-magnifying-glass-plus"></i> Preview
                        </a>
                    </div>

                    <div class="p-5">
                        {{-- b. Objectives & Cognitive Level --}}
                        <div class="mb-4 space-y-1">
                            @foreach ($question->objectives as $obj)
                                <div class="flex items-start gap-2 text-xs text-slate-600">
                                    <span
                                        class="bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-mono shrink-0">{{ $obj->tag_name }}</span>
                                    <span class="pt-0.5 format-katex">{!! $obj->description !!}</span>
                                </div>
                            @endforeach
                            <div class="mt-2">
                                <span
                                    class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-amber-50 text-amber-700 border border-amber-200 rounded">
                                    Mức độ: {{ $question->cognitiveLevel->name ?? 'Chưa xác định' }}
                                </span>
                                <span
                                    class="ml-2 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 bg-slate-100 text-slate-600 border border-slate-200 rounded">
                                    {{ $question->questionType->name }}
                                </span>
                            </div>
                        </div>

                        {{-- c. Stem (Đã thêm class format-katex) --}}
                        <div class="text-slate-800 font-medium mb-4 prose-sm max-w-none format-katex">
                            {!! $question->stem !!}
                        </div>

                        {{-- d. Choices --}}
                        @php $type = $question->questionType->code; @endphp

                        @if ($type !== 'ES')
                            <div
                                class="grid grid-cols-1 gap-2 bg-slate-50/50 p-4 rounded-lg border border-dashed border-slate-200">
                                @foreach ($question->choices as $choice)
                                    <div class="flex items-start gap-3 text-sm">
                                        {{-- Hiển thị đánh dấu đúng/sai cho MC --}}
                                        @if ($type === 'MC')
                                            <div class="mt-1">
                                                @if ($choice->is_correct)
                                                    <i class="fa-solid fa-circle-check text-green-500"></i>
                                                @else
                                                    <i class="fa-regular fa-circle text-slate-300"></i>
                                                @endif
                                            </div>
                                        @else
                                            <div class="mt-1"><i class="fa-solid fa-reply text-blue-400 text-xs"></i>
                                            </div>
                                        @endif

                                        {{-- Nội dung choice (Đã thêm class format-katex) --}}
                                        <div
                                            class="{{ $type === 'MC' && $choice->is_correct ? 'text-green-700 font-bold' : 'text-slate-600' }} format-katex">
                                            {!! $choice->content !!}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl p-10 text-center">
                    <p class="text-slate-400">Chưa có câu hỏi nào được gán cho ngữ cảnh này.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
