@extends('layouts.main')

@section('title', 'Cập nhật Loại chuyên đề')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('topic-types.index') }}" class="w-10 h-10 bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-amber-600 rounded-full flex items-center justify-center transition shadow-sm border border-slate-200">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Cập nhật Loại chuyên đề</h2>
            <p class="text-slate-500 text-sm mt-1">Chỉnh sửa thông tin: <strong class="text-blue-600">{{ $topicType->name }}</strong></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('topic-types.update', $topicType->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="p-6">
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Tên loại chuyên đề <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $topicType->name) }}" 
                        class="w-full px-4 py-2.5 border @error('name') border-rose-300 bg-rose-50 @else border-slate-300 bg-slate-50 focus:bg-white @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none" 
                        placeholder="Ví dụ: Bài tập tự luận, Câu hỏi trắc nghiệm..." required autofocus>
                    
                    @error('name')
                        <p class="mt-2 text-sm text-rose-500"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Mô tả (Không bắt buộc)</label>
                    <textarea id="description" name="description" rows="3" 
                        class="w-full px-4 py-2.5 border @error('description') border-rose-300 bg-rose-50 @else border-slate-300 bg-slate-50 focus:bg-white @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none" 
                        placeholder="Nhập mô tả chi tiết...">{{ old('description', $topicType->description) }}</textarea>
                    
                    @error('description')
                        <p class="mt-2 text-sm text-rose-500"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end gap-4">
                <a href="{{ route('topic-types.index') }}" class="inline-flex items-center justify-center px-8 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition shadow-sm">
                    Hủy bỏ
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-2.5 text-sm font-medium bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition shadow-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection