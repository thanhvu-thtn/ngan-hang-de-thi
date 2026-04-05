@extends('layouts.main')

@section('title', 'Upload Câu hỏi từ file Word')

@section('content')
<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-200">
        <h2 class="text-2xl font-bold text-slate-800 mb-2"><i class="fa-solid fa-cloud-arrow-up text-blue-600 mr-2"></i>Upload Câu hỏi</h2>
        <p class="text-slate-500 mb-6">Tải lên file Word (.docx) chứa danh sách câu hỏi theo đúng cấu trúc mẫu. Hệ thống sẽ tự động bóc tách nội dung, công thức Toán học và hình ảnh.</p>

        {{-- Nút tải file mẫu (tuỳ chọn) --}}
        <div class="mb-8 p-4 bg-blue-50 text-blue-800 rounded-lg border border-blue-100 flex items-start gap-3">
            <i class="fa-solid fa-circle-info mt-1"></i>
            <div>
                <p class="font-medium">Chưa có file mẫu?</p>
                <p class="text-sm mt-1">Vui lòng sử dụng cấu trúc chuẩn với các thẻ <strong>Begin, Type, Stem, Explanation, End,</strong> để hệ thống nhận diện chính xác.</p>
                {{-- <a href="#" class="inline-block mt-2 text-sm font-semibold text-blue-600 hover:text-blue-800 underline">Tải file Word mẫu</a> --}}
            </div>
        </div>

        <form action="{{ route('questions.upload.preview') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-6">
                <label for="word_file" class="block text-sm font-medium text-slate-700 mb-2">Chọn file Word (.docx)</label>
                <input type="file" name="word_file" id="word_file" accept=".docx" required
                    class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100 transition border border-slate-300 rounded-lg p-2">
                @error('word_file')
                    <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('questions.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition font-medium">Hủy</a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i> Phân tích file
                </button>
            </div>
        </form>
    </div>
</div>
@endsection