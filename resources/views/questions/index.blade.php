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
                <p class="text-slate-500 text-sm mt-1">Quản lý và tìm kiếm câu hỏi theo hệ thống chuyên đề được phân công.
                </p>
            </div>

            @if (!$hasNoAssignedTopics)
                <div class="flex items-center gap-3">
                    <button id="toggle-tree-btn"
                        class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition shadow-sm">
                        <i class="fa-solid fa-magnifying-glass text-blue-500"></i>
                        <span id="toggle-btn-text">Hiện bộ lọc chuyên đề</span>
                    </button>

                    <a href="{{ route('questions.create') }}"
                        class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-plus"></i> Thêm câu hỏi
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
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr
                                class="bg-slate-50/50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                                <th class="px-6 py-4 w-16 text-center">STT</th>
                                <th class="px-6 py-4 w-48">Mã định danh</th>
                                <th class="px-6 py-4">Mô tả</th>
                                <th class="px-3 py-4 w-32">Mức độ</th>
                                <th class="px-3 py-4 w-32 text-center">Trạng thái</th>
                                <th class="pl-3 py-4 w-36 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($questions as $index => $question)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4 text-center font-medium text-slate-500">
                                        {{ $questions->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block max-w-[170px] truncate font-mono text-blue-600 font-bold bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100"
                                            title="{{ $question->tag_name }}">
                                            {{ $question->tag_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-block max-w-[300px] truncate font-mono text-blue-600 font-bold bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100"
                                            title="{{ $question->name }}">
                                            {{ $question->name }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4">
                                        <span
                                            class="inline-block max-w-[150px] whitespace-nowrap px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100"
                                            title="{{ $question->cognitiveLevel->name ?? 'Không xác định' }}">
                                            {{ $question->cognitiveLevel->name ?? 'Không xác định' }}
                                        </span>
                                    </td>

                                    {{-- CELL DỮ LIỆU TRẠNG THÁI --}}
                                    <td class="px-3 py-4 text-center">
                                        @if ($question->status == 1 || $question->status == 'active')
                                            <span
                                                class="inline-flex max-w-full items-center gap-1 px-2 py-1 bg-green-50 text-green-600 rounded-md text-[10px] font-bold border border-green-200"
                                                title="Đã thẩm định">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="truncate">Đã thẩm định</span>
                                            </span>
                                        @elseif($question->status == 2)
                                            <span
                                                class="inline-flex max-w-full items-center gap-1 px-2 py-1 bg-red-50 text-red-600 rounded-md text-[10px] font-bold border border-red-200"
                                                title="Cần soạn lại">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="truncate">Cần soạn lại</span>
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex max-w-full items-center gap-1 px-2 py-1 bg-amber-50 text-amber-600 rounded-md text-[10px] font-bold border border-amber-200"
                                                title="Chưa thẩm định">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 flex-shrink-0"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="truncate">Chưa thẩm định</span>
                                            </span>
                                        @endif
                                    </td>

                                    <td class="pl-3 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1 text-slate-400">

                                            <a href="{{ route('questions.show', $question->id) }}" title="Xem chi tiết"
                                                class="hover:text-blue-600 hover:bg-blue-50 w-8 h-8 inline-flex items-center justify-center rounded-lg cursor-pointer transition-all duration-200">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>

                                            <a href="{{ route('questions.edit', $question->id) }}" title="Chỉnh sửa"
                                                class="hover:text-amber-500 hover:bg-amber-50 w-8 h-8 inline-flex items-center justify-center rounded-lg cursor-pointer transition-all duration-200">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form action="#" method="POST" class="inline-block"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Xóa"
                                                    class="hover:text-rose-600 hover:bg-rose-50 w-8 h-8 inline-flex items-center justify-center rounded-lg cursor-pointer transition-all duration-200">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Cập nhật colspan thành 6 vì đã thêm cột Trạng thái --}}
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fa-regular fa-folder-open text-4xl mb-3 text-slate-300"></i>
                                            <p>Không tìm thấy dữ liệu phù hợp với bộ lọc hiện tại.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Gắn Phân trang nếu có dữ liệu --}}
                    @if ($questions->hasPages())
                        <div class="p-4 border-t border-slate-200">
                            {{ $questions->links() }}
                        </div>
                    @endif
                </div>
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
