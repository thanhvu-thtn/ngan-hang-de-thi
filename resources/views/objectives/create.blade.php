@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-7xl">
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700">Nội dung yêu cầu (Hỗ trợ mã TeX $...$)</label>
                    <textarea id="my-editor" name="description"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>

                    <div class="flex space-x-3 mt-4">
                        <button type="button" onclick="window.previewContent()"
                            class="bg-slate-600 hover:bg-slate-700 text-white font-medium py-2 px-6 rounded-lg transition duration-150">
                            <i class="fa-solid fa-eye mr-2"></i> Xem thử (Preview)
                        </button>
                        <button type="submit"
                            class="bg-indigo-800 hover:bg-indigo-900 text-white font-medium py-2 px-8 rounded-lg shadow-md transition duration-150">
                            Lưu dữ liệu
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700">Khung xem trước hiển thị</label>
                    <div id="preview-box"
                        class="w-full h-[400px] border-2 border-dashed border-gray-200 rounded-lg p-6 bg-gray-50 overflow-auto prose prose-indigo max-w-none format-katex">
                        <span class="text-gray-400 italic">Nội dung xem trước sẽ hiển thị tại đây khi bấm nút "Xem
                            thử"...</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @vite(['resources/js/app.js', 'resources/js/editor.js'])
@endsection
