@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-5xl">
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('objectives.index', ['content_id' => $content->id]) }}"
                    class="text-gray-500 hover:text-gray-700">
                    <i class="fa-solid fa-chevron-left"></i> Quay lại
                </a>
                <h2 class="text-2xl font-bold text-gray-800">Thêm Yêu cầu cần đạt mới</h2>
            </div>
        </div>

        <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-6 rounded-r-lg">
            <p class="text-indigo-700 text-sm">
                <strong>Đang thêm cho:</strong> {{ $content->name }}
                <span class="mx-2">|</span>
                <strong>Chuyên đề:</strong> {{ $content->topic->name }}
            </p>
        </div>

        <form action="{{ route('objectives.store') }}" method="POST">
            @csrf
            <input type="hidden" name="content_id" value="{{ $content->id }}">

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="space-y-6">
                    <div>
                        <label for="tag_name" class="block text-sm font-semibold text-gray-700 mb-1">
                            Mã định danh (Tag Name) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tag_name" id="tag_name" 
                            value="{{ old('tag_name') }}"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('tag_name') border-red-500 @enderror"
                            placeholder="Ví dụ: TOAN10-CH1-01" required>
                        <p class="mt-1 text-xs text-gray-500">Mã này dùng để định danh khi bạn upload câu hỏi từ file Word (Phải là duy nhất).</p>
                        @error('tag_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nội dung yêu cầu (Hỗ trợ mã TeX)</label>
                        <textarea id="my-editor" name="description"
                            class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description') }}</textarea>

                        <div class="flex justify-end space-x-3 mt-4">
                            <button type="button" onclick="previewContent('my-editor')"
                                class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-100 transition font-bold flex items-center gap-2 border border-indigo-200">
                                <i class="fa-solid fa-eye"></i> Xem trước công thức
                            </button>
                            <button type="submit"
                                class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-8 rounded-lg shadow-md transition duration-150">
                                Lưu dữ liệu
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

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

    @include('partials.editor_script', ['selector' => '#my-editor'])
@endsection