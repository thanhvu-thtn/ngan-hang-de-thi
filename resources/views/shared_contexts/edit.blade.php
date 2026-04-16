@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-4xl">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-amber-500 rounded-lg shadow-lg shadow-amber-200">
                        <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                    </div>
                    Chỉnh sửa dữ liệu dùng chung
                </h2>
                <p class="text-slate-500 text-sm mt-1">Cập nhật nội dung cho mã: <span
                        class="font-bold text-slate-700">{{ $context->tag_name }}</span></p>
            </div>
            <button onclick="window.history.back();" class="text-slate-500 hover:text-slate-700 font-medium">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </button>
        </div>

        <form action="{{ route('shared-contexts.update', $context->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

                {{-- Mã định danh --}}
                <div class="mb-6">
                    <label for="tag_name" class="block text-sm font-bold text-slate-700 mb-2">Mã định danh <span
                            class="text-red-500">*</span></label>
                    <input type="text" name="tag_name" id="tag_name" value="{{ old('tag_name', $context->tag_name) }}"
                        required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                    @error('tag_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nội dung văn bản với căn lề trái/phải --}}
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label for="content" class="text-sm font-bold text-slate-700">
                            Nội dung văn bản / Ngữ liệu <span class="text-red-500">*</span>
                        </label>

                        <button type="button" onclick="window.previewContent('editor-content')"
                            class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                            <i class="fa-solid fa-eye"></i> Preview
                        </button>
                    </div>

                    <textarea name="content" id="editor-content" rows="10"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all">{{ old('content', $context->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ghi chú --}}
                <div class="mb-6">
                    <label for="note" class="block text-sm font-bold text-slate-700 mb-2">Ghi chú nội bộ</label>
                    <input type="text" name="note" id="note" value="{{ old('note', $context->note) }}"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-indigo-500 transition-all shadow-sm">
                </div>

                <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                    <a href="{{ route('shared-contexts.show', $context->id) }}"
                        class="px-6 py-2.5 text-slate-600 font-bold hover:bg-slate-100 rounded-xl transition">Hủy</a>
                    <button type="submit"
                        class="px-8 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-bold transition shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu thay đổi
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    @include('partials.editor_script', ['selector' => '#editor-content'])
@endpush
