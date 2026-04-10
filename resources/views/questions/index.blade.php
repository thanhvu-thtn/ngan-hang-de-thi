@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6">
        {{-- Header --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">
            {{-- Bên trái: Tiêu đề (Chiếm không gian tự nhiên) --}}
            <div class="flex-shrink-0">
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-layer-group text-white text-sm"></i>
                    </div>
                    Ngân hàng câu hỏi
                </h2>
                <p class="text-slate-500 text-sm mt-1">Quản lý hệ thống câu hỏi chuyên đề.</p>
            </div>

            {{-- Bên phải: Cụm 3 nút cùng kích thước, cùng hàng --}}
            @if (!$hasNoAssignedTopics)
                <div class="flex flex-wrap items-center justify-end gap-3 flex-1">

                    {{-- 1. Ô tìm kiếm (Chiều cao h-11) --}}
                    <form action="{{ route('questions.index') }}" method="GET" class="relative flex items-center h-11">
                        @if (request('filter_objective_ids'))
                            @foreach (request('filter_objective_ids') as $id)
                                <input type="hidden" name="filter_objective_ids[]" value="{{ $id }}">
                            @endforeach
                        @endif

                        <div class="relative h-full group">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i
                                    class="fa-solid fa-hashtag text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="tag_name" value="{{ request('tag_name') }}"
                                class="h-full w-44 xl:w-56 pl-9 pr-16 bg-white border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all shadow-sm"
                                placeholder="Mã câu hỏi...">

                            <button type="submit"
                                class="absolute right-1.5 top-1.5 bottom-1.5 px-3 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-700 transition flex items-center gap-1">
                                Tìm
                            </button>
                        </div>
                    </form>

                    {{-- 2. Nút Bộ lọc (Chiều cao h-11) --}}
                    <button id="toggle-tree-btn"
                        class="h-11 flex items-center gap-2 px-4 bg-white border border-slate-300 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-50 transition shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-filter text-blue-500"></i>
                        <span>Bộ lọc chuyên đề</span>
                    </button>

                    {{-- 3. Nút Thêm mới (Chiều cao h-11) --}}
                    <a href="{{ route('questions.create') }}"
                        class="h-11 flex items-center gap-2 px-5 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 whitespace-nowrap">
                        <i class="fa-solid fa-plus"></i> Thêm mới
                    </a>

                </div>
            @endif
        </div>

        @if ($hasNoAssignedTopics)
            <div
                class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center flex flex-col items-center justify-center">
                <div
                    class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-5 text-4xl shadow-inner">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 mb-2">Chưa có quyền truy cập</h3>
                <p class="text-slate-500">Vui lòng liên hệ Thầy
                    {{ auth()->user()->subject->headTeacher->name ?? 'trưởng bộ môn' }} để được phân công.</p>
            </div>
        @else
            {{-- BỘ LỌC CHUYÊN ĐỀ (TREE VIEW) --}}
            <div id="tree-view-container"
                class="hidden mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6 transition-all duration-300 relative animate-fade-in-down">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <h3 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa-solid fa-filter text-blue-500"></i> Cấu trúc chuyên đề môn
                        {{ auth()->user()->subject->name ?? 'học' }}
                    </h3>
                    <button id="close-tree-btn"
                        class="text-slate-400 hover:text-rose-500 transition px-2 py-1 rounded-lg hover:bg-rose-50">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                {{-- FORM GỬI LỆNH LỌC DỮ LIỆU --}}
                <form action="{{ route('questions.index') }}" method="GET" id="filter-form">

                    {{-- GỌI COMPONENT TREEVIEW ĐÃ TẠO BƯỚC TRƯỚC --}}
                    @include('questions.partials.treeview', [
                        'treeByGrade' => $treeByGrade,
                        'showCount' => true,
                        'inputName' => 'filter_objective_ids[]',
                    ])

                    <div class="mt-4 flex justify-end">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-filter"></i> Lọc câu hỏi
                        </button>
                    </div>
                </form>
            </div>

            {{-- BẢNG CÂU HỎI --}}
            {{-- KHU VỰC HIỂN THỊ DỮ LIỆU --}}
            @if ($isFiltering)
                {{-- Đã bấm lọc: Hiện bảng câu hỏi --}}
                {{-- KHU VỰC BẢNG DỮ LIỆU --}}
        @if (!$hasNoAssignedTopics)
            @if ($questions->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                        <i class="fa-solid fa-inbox"></i>
                    </div>
                    <h3 class="text-lg font-medium text-slate-800 mb-1">Không tìm thấy câu hỏi nào</h3>
                    <p class="text-slate-500">Chưa có câu hỏi nào thuộc bộ lọc này.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/80 border-b border-slate-200">
                                <tr>
                                    <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">Mã định danh</th>
                                    <th class="px-5 py-4 text-left text-sm font-semibold text-slate-600">Mô tả / Câu hỏi</th>
                                    {{-- CỘT MỚI: LOẠI --}}
                                    <th class="px-5 py-4 text-center text-sm font-semibold text-slate-600">Loại</th>
                                    <th class="px-5 py-4 text-center text-sm font-semibold text-slate-600">Mức độ</th>
                                    <th class="px-5 py-4 text-center text-sm font-semibold text-slate-600">Trạng thái</th>
                                    <th class="px-5 py-4 text-right text-sm font-semibold text-slate-600">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($questions as $q)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        {{-- 1. Mã định danh --}}
                                        <td class="px-5 py-4 align-top text-sm text-slate-800 font-medium whitespace-nowrap">
                                            {{-- Cắt hiển thị 8 ký tự, rê chuột vào sẽ thấy toàn bộ --}}
                                            <span title="{{ $q->tag_name }} cursor-help">
                                                {{ \Illuminate\Support\Str::limit($q->tag_name, 8, '...') }}
                                            </span>
                                            @if($q->shared_context_id)
                                                <div class="mt-1 text-xs text-indigo-600 italic">Có dữ liệu chung</div>
                                            @endif
                                        </td>

                                        {{-- 2. Mô tả / Câu hỏi --}}
                                        <td class="px-5 py-4 align-top">
                                            {{-- Gộp name và stem vào title để khi hover xem được full --}}
                                            <div title="{{ $q->name ? $q->name . ' - ' : '' }}{{ strip_tags($q->stem) }}">
                                                @if ($q->name)
                                                    <div class="text-sm font-medium text-slate-800 mb-1 format-katex line-clamp-1">
                                                        {{ $q->name }}
                                                    </div>
                                                @endif
                                        
                                            </div>
                                            
                                
                                        </td>

                                        {{-- 3. CỘT MỚI: Loại (MC, TF, SA, ES...) --}}
                                        <td class="px-5 py-4 align-top text-center text-sm text-slate-800 font-semibold">
                                            {{ $q->questionType->code ?? '-' }}
                                        </td>

                                        {{-- 4. Mức độ (Text bình thường) --}}
                                        <td class="px-5 py-4 align-top text-center text-sm text-slate-700">
                                            {{ $q->cognitiveLevel->name ?? '-' }}
                                        </td>

                                        {{-- 5. Trạng thái (Giữ nguyên thẻ label màu xanh/vàng do tính chất status cần nổi bật) --}}
                                        <td class="px-5 py-4 align-top text-center">
                                            @if ($q->status === 'approved')
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                                                    Đã duyệt
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-[11px] font-semibold bg-amber-100 text-amber-700">
                                                    Chờ duyệt
                                                </span>
                                            @endif
                                        </td>

                                        {{-- 6. Thao tác (Giữ nguyên) --}}
                                        <td class="px-5 py-4 align-top text-right whitespace-nowrap">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('questions.show', $q->id) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Xem chi tiết">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{ route('questions.edit',$q->id) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Sửa câu hỏi">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                                <button type="button" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Xóa">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Phân trang --}}
                    @if ($questions->hasPages())
                        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50">
                            {{ $questions->links() }}
                        </div>
                    @endif
                </div>
            @endif
        @endif
            @else
                {{-- Chưa bấm lọc: Màn hình chờ mặc định --}}
                <div
                    class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center flex flex-col items-center justify-center">
                    <div
                        class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-5 text-4xl shadow-inner">
                        <i class="fa-solid fa-magnifying-glass-chart"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Chưa có dữ liệu hiển thị</h3>
                    <p class="text-slate-500">Vui lòng mở <strong class="text-slate-700">"Hiện bộ lọc chuyên đề"</strong>,
                        chọn các mục tiêu đánh giá và bấm Lọc để xem danh sách câu hỏi.</p>
                </div>
            @endif
        @endif
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }

        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    {{-- GỌI JS CỦA TREEVIEW VÀO ĐÂY --}}
    @include('questions.partials.treeview_js')

    @include('questions.partials.index_js')
@endsection
