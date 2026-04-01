@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-layer-group text-white text-sm"></i>
                    </div>
                    Ngân hàng câu hỏi
                </h2>
                <p class="text-slate-500 text-sm mt-1">Quản lý và tìm kiếm câu hỏi theo hệ thống chuyên đề được phân công.</p>
            </div>

            @if (!$hasNoAssignedTopics)
                <div class="flex items-center gap-3">
                    <button id="toggle-tree-btn"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition shadow-sm">
                        <i class="fa-solid fa-magnifying-glass text-blue-500"></i>
                        <span id="toggle-btn-text">Hiện bộ lọc chuyên đề</span>
                    </button>

                    <a href="{{ route('questions.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-plus"></i> Thêm câu hỏi
                    </a>
                </div>
            @endif
        </div>

        @if ($hasNoAssignedTopics)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-5 text-4xl shadow-inner">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 mb-2">Chưa có quyền truy cập</h3>
                <p class="text-slate-500">Vui lòng liên hệ Thầy {{ auth()->user()->subject->headTeacher->name ?? 'trưởng bộ môn' }} để được phân công.</p>
            </div>
        @else
            {{-- BỘ LỌC CHUYÊN ĐỀ (TREE VIEW) --}}
            <div id="tree-view-container" class="hidden mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transition-all duration-300 relative animate-fade-in-down">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-filter text-blue-500"></i> Cấu trúc chuyên đề môn {{ auth()->user()->subject->name ?? 'học' }}
                    </h3>
                    <button id="close-tree-btn" class="text-slate-400 hover:text-rose-500 transition px-2 py-1 rounded-lg hover:bg-rose-50">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <div class="space-y-6 max-h-[500px] overflow-y-auto custom-scrollbar pr-4">
                    @forelse($treeByGrade as $grade => $topics)
                        {{-- CẤP 1: KHỐI (Grade Node) --}}
                        <div class="grade-node border-l-2 border-blue-200 pl-4 ml-2 mb-4">
                            <h3 class="grade-header font-bold text-lg text-blue-700 mb-3 flex items-center gap-2.5 cursor-pointer hover:text-blue-500 transition-colors">
                                <i class="fa-solid fa-caret-down text-blue-400 transition-transform duration-200"></i>
                                Khối {{ $grade }}
                            </h3>

                            <div class="topic-list space-y-5 pl-2">
                                @foreach ($topics as $topic)
                                    {{-- CẤP 2: TOPIC --}}
                                    <div class="topic-node border-l border-slate-300 pl-6 ml-1 relative">
                                        <div class="absolute w-4 h-px bg-slate-300 left-0 top-3.5"></div>
                                        <div class="topic-header font-semibold text-slate-800 text-base mb-2.5">
                                            {{ $topic->name }}
                                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 ml-2">
                                                {{ $topic->topicType->name ?? 'Cơ bản' }}
                                            </span>
                                        </div>

                                        <div class="content-list space-y-2 mt-2">
                                            @forelse($topic->contents as $content)
                                                {{-- CẤP 3: CONTENT --}}
                                                <div class="content-node border-l border-slate-200 pl-6 ml-3 relative group">
                                                    <div class="absolute w-4 h-px bg-slate-200 left-0 top-3"></div>
                                                    <div class="content-toggle flex items-center gap-2 cursor-pointer py-0.5" data-content-id="{{ $content->id }}">
                                                        <i class="fa-solid fa-caret-right text-slate-400 group-hover:text-blue-500 transition-transform duration-200"></i>
                                                        <div class="font-medium text-slate-700 group-hover:text-blue-700 text-sm">
                                                            <i class="fa-regular fa-folder-open text-amber-400 mr-1.5"></i> {{ $content->name }}
                                                        </div>
                                                    </div>

                                                    {{-- CẤP 4: OBJECTIVE --}}
                                                    <div class="objective-list hidden space-y-2.5 mt-3 pl-3 pr-2 pb-2 animate-fade-in">
                                                        @forelse($content->objectives as $objective)
                                                            <a href="#" class="flex items-start gap-3 justify-between group/obj p-3.5 bg-white border border-slate-200 rounded-xl shadow-sm hover:shadow-md hover:bg-blue-50/60 hover:border-blue-300 transition-all duration-200">
                                                                <div class="text-sm text-slate-700 leading-relaxed group-hover/obj:text-blue-800 flex-1">
                                                                    <span class="inline-block math-content w-full">{!! $objective->description !!}</span>
                                                                </div>
                                                                <span class="shrink-0 inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold border {{ $objective->questions_count > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200' }}">
                                                                    {{ $objective->questions_count }} câu
                                                                </span>
                                                            </a>
                                                        @empty
                                                            <div class="pl-4 text-xs text-slate-400 italic py-2">Chưa có mục tiêu</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="pl-9 text-xs text-slate-400 italic">Chưa có nội dung</div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500">Không tìm thấy chuyên đề.</div>
                    @endforelse
                </div>
            </div>

            {{-- BẢNG CÂU HỎI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4 w-16 text-center">STT</th>
                            <th class="px-6 py-4 w-48">Mã định danh</th>
                            <th class="px-6 py-4">Mô tả</th>
                            <th class="px-6 py-4 w-40">Mức độ</th>
                            <th class="px-6 py-4 w-36 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 text-center">01</td>
                            <td class="px-6 py-4"><span class="font-mono text-blue-600 font-bold bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100">VL#10#CB#MD#CH01#</span></td>
                            <td class="px-6 py-4 text-slate-700">Dữ liệu mẫu...</td>
                            <td class="px-6 py-4"><span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100">Thông hiểu</span></td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-1.5 text-slate-400">
                                    <i class="fa-solid fa-eye hover:text-blue-600 p-2 cursor-pointer"></i>
                                    <i class="fa-solid fa-trash-can hover:text-rose-600 p-2 cursor-pointer"></i>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
        .animate-fade-in-down { animation: fadeInDown 0.3s ease-out; }
        @keyframes fadeInDown { 0% { opacity: 0; transform: translateY(-10px); } 100% { opacity: 1; transform: translateY(0); } }
        
        /* Cấu hình xoay icon khi đóng/mở */
        .grade-node.is-closed .grade-header i.fa-caret-down { transform: rotate(-90deg); }
        .grade-node.is-closed .topic-list { display: none; }
        .content-node.is-open .content-toggle i.fa-caret-right { transform: rotate(90deg); color: #3b82f6; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const treeContainer = document.getElementById('tree-view-container');

            // Hàm Render Math
            function refreshMath() {
                if (typeof renderMathInElement === 'function' && treeContainer) {
                    renderMathInElement(treeContainer, {
                        delimiters: [
                            {left: '$$', right: '$$', display: true},
                            {left: '$', right: '$', display: false},
                            {left: '\\(', right: '\\)', display: false}
                        ],
                        throwOnError: false
                    });
                }
            }

            // 1. Nút Hiện/Ẩn bộ lọc chính
            const toggleBtn = document.getElementById('toggle-tree-btn');
            const btnText = document.getElementById('toggle-btn-text');
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const isHidden = treeContainer.classList.contains('hidden');
                    treeContainer.classList.toggle('hidden');
                    btnText.innerText = isHidden ? 'Ẩn bộ lọc chuyên đề' : 'Hiện bộ lọc chuyên đề';
                    if (isHidden) refreshMath();
                });
            }

            // 2. Lệnh đóng cho nút X
            document.getElementById('close-tree-btn')?.addEventListener('click', () => toggleBtn.click());

            // 3. XỬ LÝ ĐÓNG MỞ KHỐI (Grade) - FIX TẠI ĐÂY
            const gradeHeaders = document.querySelectorAll('.grade-header');
            gradeHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const gradeNode = this.closest('.grade-node');
                    gradeNode.classList.toggle('is-closed');
                });
            });

            // 4. Xử lý đóng mở Content (Mục tiêu)
            const contentToggles = document.querySelectorAll('.content-toggle');
            contentToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const contentNode = this.closest('.content-node');
                    const objectiveList = contentNode.querySelector('.objective-list');
                    if (objectiveList) {
                        const isOpening = objectiveList.classList.contains('hidden');
                        objectiveList.classList.toggle('hidden');
                        contentNode.classList.toggle('is-open');
                        if (isOpening) refreshMath();
                    }
                });
            });

            refreshMath();
        });
    </script>
@endsection