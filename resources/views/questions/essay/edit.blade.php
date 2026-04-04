@extends('layouts.main')

@section('content')
    <div class="max-w-5xl mx-auto py-10 px-4">

        {{-- THANH TIẾN TRÌNH / HEADER --}}
        <div class="mb-8">
            <div class="flex items-center gap-4 text-sm font-medium text-slate-500">
                <span class="flex items-center gap-2 text-emerald-600"><i class="fa-solid fa-circle-check"></i> Bước 1: Thiết
                    lập cơ bản</span>
                <i class="fa-solid fa-chevron-right text-xs"></i>
                <span class="flex items-center gap-2 text-blue-600 underline underline-offset-8 decoration-2 font-bold">Bước
                    2: Cập nhật nội dung Tự luận</span>
            </div>
        </div>

        {{-- ĐƯA FORM LÊN TRƯỚC ĐỂ BAO BỌC LUÔN PHẦN THÔNG TIN BƯỚC 1 --}}
        <form action="{{ route('questions.es.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- TÓM TẮT THÔNG TIN BƯỚC 1 CÙNG TRẠNG THÁI --}}
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 flex flex-wrap items-center gap-6 text-sm">
                <div><span class="text-slate-500">Tên:</span> <strong class="text-slate-700">{{ $question->name }}</strong>
                </div>
                <div><span class="text-slate-500">Mã/Tag:</span> <code
                        class="bg-slate-200 px-1.5 py-0.5 rounded text-blue-700 font-bold">{{ $question->tag_name }}</code>
                </div>
                <div><span class="text-slate-500">Loại:</span> <strong
                        class="text-indigo-600">{{ $question->questionType->name ?? 'Tự luận' }}</strong></div>

                {{-- COMBOBOX TRẠNG THÁI (STATUS) --}}
                <div class="flex items-center gap-2">
                    <span class="text-slate-500">Trạng thái:</span>
                    <select name="status"
                        class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-1.5 font-semibold transition-colors disabled:bg-slate-200 disabled:text-slate-500 disabled:cursor-not-allowed"
                        @cannot('tham-dinh-cau-hoi') disabled title="Bạn không có quyền thay đổi trạng thái" @endcannot>
                        <option value="0" {{ old('status', $question->status) == 0 ? 'selected' : '' }}>Chưa thẩm định
                        </option>
                        <option value="1" {{ old('status', $question->status) == 1 ? 'selected' : '' }}>Đã thẩm định
                        </option>
                        <option value="2" {{ old('status', $question->status) == 2 ? 'selected' : '' }}>Cần xem lại
                        </option>
                    </select>

                    {{-- Nếu không có quyền (select bị disabled), ta dùng input ẩn để gửi kèm status cũ, tránh bị mất dữ liệu --}}
                    @cannot('tham-dinh-cau-hoi')
                        <input type="hidden" name="status" value="{{ $question->status }}">
                    @endcannot
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8">

                {{-- PHẦN ĐỀ BÀI (STEM) --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-blue-500"></i> Nội dung đề bài (Câu hỏi) <span
                                class="text-rose-500">*</span>
                        </label>
                        <button type="button" onclick="previewContent('editor-stem')"
                            class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-100 transition font-bold flex items-center gap-2 border border-indigo-200">
                            <i class="fa-solid fa-eye"></i> Xem trước công thức
                        </button>
                    </div>

                    {{-- Đổ dữ liệu cũ của đề bài vào đây --}}
                    <textarea name="stem" id="editor-stem" rows="10" required>{{ old('stem', $question->stem) }}</textarea>
                    @error('stem')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PHẦN HƯỚNG DẪN CHẤM / ĐÁP ÁN MẪU --}}
                <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                    <div class="flex justify-between items-center mb-4">
                        <label class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-regular fa-lightbulb text-amber-500"></i> Hướng dẫn chấm / Đáp án gợi ý
                        </label>
                        <button type="button" onclick="previewContent('editor-explanation')"
                            class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-100 transition font-bold flex items-center gap-2 border border-indigo-200">
                            <i class="fa-solid fa-eye"></i> Xem trước công thức
                        </button>
                    </div>

                    {{-- Đổ dữ liệu cũ của lời giải. --}}
                    <textarea name="explanation" id="editor-explanation" rows="6">{{ old('explanation', $question->explanation->content ?? '') }}</textarea>
                    @error('explanation')
                        <p class="text-rose-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NÚT ĐIỀU HƯỚNG --}}
                {{-- NÚT ĐIỀU HƯỚNG --}}
                <div
                    class="flex flex-wrap justify-between items-center gap-4 bg-slate-100 p-6 rounded-2xl border border-slate-200 mt-2">
                    {{-- Trái: Nút Quay lại --}}
                    <a href="{{ route('questions.edit', $question->id) }}"
                        class="text-slate-600 hover:text-slate-800 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại Bước 1
                    </a>

                    {{-- Phải: Nhóm các nút hành động --}}
                    <div class="flex flex-wrap items-center gap-3">

                        {{-- Nút Xuất PDF --}}
                        <a href="{{ route('questions.es.printpdf', $question->id) }}" target="_blank"
                            class="px-5 py-3 bg-rose-50 text-rose-600 font-bold rounded-xl hover:bg-rose-100 transition border border-rose-200 flex items-center gap-2"
                            title="Mở tab mới để in hoặc xem PDF">
                            <i class="fa-regular fa-file-pdf"></i> Xuất PDF
                        </a>

                        {{-- Nút Xuất Word (Cần thay '#' bằng route thực tế nếu bạn đã làm route in Word) --}}
                        <a href="{{ route('questions.es.printWord', $question->id) }}" target="_blank"
                            class="px-5 py-3 bg-sky-50 text-sky-600 font-bold rounded-xl hover:bg-sky-100 transition border border-sky-200 flex items-center gap-2"
                            title="Tải file Word về máy">
                            <i class="fa-regular fa-file-word"></i> Xuất Word
                        </a>

                        {{-- Nút Cập nhật (Nút chính - Primary Action) --}}
                        <button type="submit"
                            class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2 ml-2">
                            Cập nhật câu hỏi <i class="fa-solid fa-floppy-disk"></i>
                        </button>

                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- MODAL XEM TRƯỚC (PREVIEW) --}}
    <div id="preview-modal"
        class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm transition-opacity">
        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-slate-200 overflow-hidden">
            {{-- Header Modal --}}
            <div class="flex justify-between items-center bg-slate-50 p-4 border-b border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-microscope text-indigo-500"></i> Xem trước hiển thị thực tế
                </h3>
                <button type="button" onclick="closePreview()" class="text-slate-400 hover:text-rose-500 transition">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <div id="preview-body" class="p-8 overflow-y-auto text-slate-800 text-base leading-relaxed katex-scan"
                style="min-height: 250px;">
            </div>

            {{-- Footer Modal --}}
            <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-end">
                <button type="button" onclick="closePreview()"
                    class="px-6 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition">
                    Đóng màn hình này
                </button>
            </div>
        </div>
    </div>

    @include('questions.essay.partials.editor_script') {{-- Script khởi tạo TinyMCE và hàm preview --}}
@endsection
