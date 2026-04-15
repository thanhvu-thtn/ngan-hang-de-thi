@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-7xl">
        {{-- Header & Action --}}
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-indigo-500 rounded-lg shadow-lg shadow-indigo-200">
                        <i class="fa-solid fa-layer-group text-white text-sm"></i>
                    </div>
                    Quản lý Dữ liệu dùng chung
                </h2>
                <p class="text-slate-500 text-sm mt-1">Danh sách các ngữ cảnh, văn bản dùng cho chùm câu hỏi.</p>
            </div>

            <a href="{{ route('shared-contexts.create') }}"
                class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all shadow-md shadow-blue-100 gap-2">
                <i class="fa-solid fa-plus"></i>
                Thêm dữ liệu mới
            </a>
        </div>

        {{-- Bộ lọc tìm kiếm --}}
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 mb-6">
            <form action="{{ route('shared-contexts.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-[300px]">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 text-sm"
                        placeholder="Tìm theo mã định danh hoặc nội dung...">
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-slate-800 text-white text-sm font-bold rounded-xl hover:bg-slate-900 transition-colors">
                    Lọc dữ liệu
                </button>
                @if (request('search'))
                    <a href="{{ route('shared-contexts.index') }}"
                        class="text-sm text-slate-500 hover:text-rose-500 underline">Xóa lọc</a>
                @endif
            </form>
        </div>

        {{-- Bảng danh sách --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16 text-center">STT
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Mã định danh
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nội dung tóm tắt
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center w-32">Số
                            câu hỏi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right w-32">Thao
                            tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($contexts as $index => $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-500 text-center">
                                {{ ($contexts->currentPage() - 1) * $contexts->perPage() + $index + 1 }}
                            </td>
                            <td class="px-6 py-4">
                                {{-- Thêm thẻ a trỏ đến route show --}}
                                <a href="{{ route('shared-contexts.show', $item->id) }}" class="group"
                                    title="Xem chi tiết nội dung này">
                                    <span
                                        class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded text-xs uppercase cursor-pointer group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                        {{ $item->tag_name }}
                                    </span>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-700 line-clamp-2">
                                    {{-- Strip tags để hiển thị text thuần từ TinyMCE --}}
                                    {{ Str::limit(strip_tags($item->content), 150) }}
                                </div>
                                @if ($item->note)
                                    <p class="text-[11px] text-slate-400 mt-1 italic">
                                        <i class="fa-solid fa-paperclip mr-1"></i>{{ $item->note }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 text-xs font-bold border border-slate-200">
                                    {{ $item->questions_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('shared-contexts.edit', $item->id) }}"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('shared-contexts.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa ngữ cảnh này?');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Xóa">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-slate-400">
                                    <i class="fa-solid fa-folder-open text-4xl mb-3"></i>
                                    <p>Chưa có dữ liệu dùng chung nào được tạo.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        <div class="mt-6">
            {{ $contexts->links() }}
        </div>
    </div>
@endsection
