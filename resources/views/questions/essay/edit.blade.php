@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    
    {{-- THANH TIẾN TRÌNH / HEADER --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 text-sm font-medium text-slate-500">
            <span class="flex items-center gap-2 text-emerald-600"><i class="fa-solid fa-circle-check"></i> Bước 1: Thiết lập cơ bản</span>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="flex items-center gap-2 text-blue-600 underline underline-offset-8 decoration-2 font-bold">Bước 2: Cập nhật nội dung Tự luận</span>
        </div>
    </div>

    {{-- TÓM TẮT THÔNG TIN BƯỚC 1 --}}
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-6 text-sm">
        <div><span class="text-slate-500">Tên:</span> <strong class="text-slate-700">{{ $question->name }}</strong></div>
        <div><span class="text-slate-500">Mã/Tag:</span> <code class="bg-slate-200 px-1.5 py-0.5 rounded text-blue-700 font-bold">{{ $question->tag_name }}</code></div>
        <div><span class="text-slate-500">Loại:</span> <strong class="text-indigo-600">{{ $question->questionType->name ?? 'Tự luận' }}</strong></div>
    </div>

    {{-- FORM CẬP NHẬT --}}
    {{-- LƯU Ý: Nhớ tạo route questions.es.update trong web.php --}}
    <form action="{{ route('questions.es.update', $question->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-8">
            
            {{-- PHẦN ĐỀ BÀI (STEM) --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-blue-500"></i> Nội dung đề bài (Câu hỏi) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="previewContent('editor-stem')" 
                        class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-100 transition font-bold flex items-center gap-2 border border-indigo-200">
                        <i class="fa-solid fa-eye"></i> Xem trước công thức
                    </button>
                </div>
                
                {{-- Đổ dữ liệu cũ của đề bài vào đây --}}
                <textarea name="stem" id="editor-stem" rows="10" required>{{ old('stem', $question->stem) }}</textarea>
                @error('stem') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
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
                @error('explanation') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- NÚT ĐIỀU HƯỚNG --}}
            <div class="flex justify-between items-center bg-slate-100 p-6 rounded-2xl border border-slate-200">
                <a href="{{ route('questions.edit', $question->id) }}" class="text-slate-600 hover:text-slate-800 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại Bước 1
                </a>
                
                <button type="submit" 
                    class="px-10 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2">
                    Cập nhật câu hỏi <i class="fa-solid fa-floppy-disk"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- MODAL XEM TRƯỚC (PREVIEW) --}}
<div id="preview-modal" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-slate-200 overflow-hidden">
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
        <div id="preview-body" class="p-8 overflow-y-auto text-slate-800 text-base leading-relaxed katex-scan" style="min-height: 250px;">
        </div>
        
        {{-- Footer Modal --}}
        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="closePreview()" class="px-6 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition">
                Đóng màn hình này
            </button>
        </div>
    </div>
</div>

@include('questions.essay.partials.editor_script') {{-- Script khởi tạo TinyMCE và hàm preview --}} 
@endsection