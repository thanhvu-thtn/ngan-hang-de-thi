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

                {{-- FORM GỬI LỆNH LỌC DỮ LIỆU --}}
                <form action="{{ route('questions.index') }}" method="GET" id="filter-form">
                    
                    {{-- GỌI COMPONENT TREEVIEW ĐÃ TẠO BƯỚC TRƯỚC --}}
                    @include('questions.partials.treeview', [
                        'treeByGrade' => $treeByGrade,
                        'showCount' => true,
                        'inputName' => 'filter_objective_ids[]'
                    ])
                    
                    <div class="mt-4 flex justify-end">
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-filter"></i> Lọc câu hỏi
                        </button>
                    </div>
                </form>
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
    </style>

    {{-- GỌI JS CỦA TREEVIEW VÀO ĐÂY --}}
    @include('questions.partials.treeview_js')

    @include('questions.partials.index_js')
@endsection