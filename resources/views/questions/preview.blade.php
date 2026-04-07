@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fa-solid fa-file-import text-blue-500"></i> Xem trước dữ liệu câu hỏi
            </h2>
            <p class="text-slate-500 mt-1">Vui lòng kiểm tra lại cấu trúc và nội dung câu hỏi trước khi lưu vào hệ thống.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('questions.index') }}" class="px-5 py-2.5 bg-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-300 transition">
                Hủy bỏ
            </a>
            <form action="#" method="POST">
                @csrf
                {{-- Nút này tạm thời để mờ hoặc link tới route lưu thật sau này --}}
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Xác nhận Import
                </button>
            </form>
        </div>
    </div>

    {{-- Danh sách dữ liệu --}}
    <div class="space-y-6">
        @foreach($structuredData as $index => $item)
            
            @if($item['type'] === 'SharedContext')
                {{-- KHỐI SHARED CONTEXT --}}
                <div class="bg-white border border-indigo-200 rounded-2xl shadow-sm overflow-hidden">
                    {{-- Tiêu đề Context --}}
                    <div class="bg-indigo-50 px-6 py-4 border-b border-indigo-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-indigo-800">
                            <i class="fa-solid fa-layer-group mr-2"></i> Cụm câu hỏi dùng chung (Shared Context)
                        </h3>
                        <span class="bg-indigo-200 text-indigo-800 text-xs px-3 py-1 rounded-full font-bold">
                            {{ count($item['questions']) }} Câu hỏi con
                        </span>
                    </div>
                    
                    {{-- Nội dung Context --}}
                    <div class="p-6">
                        @if(!empty($item['context_data']['tag_name']))
                            <span class="bg-slate-200 text-slate-700 text-xs px-2 py-1 rounded border border-slate-300">
                                Tag: {{ $item['context_data']['tag_name'] }}
                            </span>
                        @else
                            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded border border-orange-300 font-medium">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Tag: Nội dung này chưa được nhập hoặc không upload được
                            </span>
                        @endif
                        <div class="prose max-w-none text-slate-700 katex-scan mb-4">
                            {!! $item['context_data']['content'] !!}
                        </div>
                        
                        {{-- Danh sách câu hỏi con (THỤT LỀ) --}}
                        <div class="mt-6 pl-8 border-l-4 border-indigo-200 space-y-6">
                            @foreach($item['questions'] as $qIndex => $q)
                                @include('questions.partials._preview_question', ['q' => $q, 'index' => $qIndex + 1])
                            @endforeach
                        </div>
                    </div>
                </div>

            @elseif($item['type'] === 'IndependentQuestion')
                {{-- KHỐI CÂU HỎI ĐỘC LẬP --}}
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full font-bold">
                            Câu hỏi độc lập
                        </span>
                    </div>
                    @include('questions.partials._preview_question', ['q' => $item['question_data'], 'index' => $index + 1])
                </div>
            @endif

        @endforeach
    </div>
</div>
@endsection