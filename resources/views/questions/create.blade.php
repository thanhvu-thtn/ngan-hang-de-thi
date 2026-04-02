@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    
    {{-- Header --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
            <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                <i class="fa-solid fa-plus text-white text-sm"></i>
            </div>
            Thêm mới câu hỏi
        </h2>
        <p class="text-slate-500 text-sm mt-1">Thiết lập thông tin ban đầu câu hỏi.</p>
    </div>

    {{-- Bắt đầu Form --}}
    <form action="{{ route('questions.storeSetup') }}" method="POST" id="question-form">
        @csrf

        {{-- BƯỚC 1: THIẾT LẬP (Sử dụng partials) --}}
        <div id="step-1-container" class="animate-fade-in-down">
            @include('questions.partials.step1_setup')
        </div>

        {{-- BƯỚC 2: SOẠN THẢO NỘI DUNG (Tạm thời ẩn) --}}
        <div id="step-2-container" class="hidden animate-fade-in-down">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <div class="flex justify-between items-center mb-6 pb-3 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-emerald-500"></i> BƯỚC 2: NỘI DUNG
                    </h3>
                    <button type="button" id="btn-prev-step" class="text-sm px-4 py-2 border border-slate-300 text-slate-600 rounded-lg hover:bg-slate-50 transition">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại thiết lập
                    </button>
                </div>

                {{-- Khung nhập nội dung câu hỏi chung (Phần dẫn) --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nội dung câu hỏi (Phần dẫn) <span class="text-rose-500">*</span>
                    </label>
                    <textarea id="question_content" name="content" class="tinymce-editor form-control"></textarea>
                </div>

                {{-- KHU VỰC CHỨA CÁC ĐÁP ÁN (Sẽ include các loại câu hỏi ở bước sau) --}}
                <div id="answer-blocks-container" class="mb-6">
                    {{-- Sắp tới chúng ta sẽ đặt @include('questions.partials.type_mc') ở đây --}}
                </div>

                {{-- Nút Submit --}}
                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('questions.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 font-medium transition">
                        Hủy bỏ
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Lưu câu hỏi
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>

{{-- Gọi các file JS --}}
@include('questions.partials.treeview_js')
{{-- @include('questions.partials.create_js') <-- Sẽ thêm ở bước sau --}}

@endsection