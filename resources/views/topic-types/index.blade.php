@extends('layouts.main')

@section('title', 'Quản lý Loại chuyên đề')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-layer-group text-blue-600 mr-2"></i>Quản
                    lý Loại chuyên đề</h2>
                <p class="text-slate-500 text-sm mt-1">Danh sách phân loại chuyên đề (VD: Trắc nghiệm, Tự luận...)</p>
            </div>
            <a href="{{ route('topic-types.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Thêm loại mới
            </a>
        </div>

        @if (session('success'))
            <div
                class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-200 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-xl"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 text-rose-600 p-4 rounded-lg mb-6 border border-rose-200 flex items-center gap-3">
                <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 w-20 text-center">STT</th>
                        <th class="px-6 py-4 w-1/4">Tên Loại Chuyên Đề</th>
                        <th class="px-6 py-4">Mô tả</th>
                        <th class="px-6 py-4 w-40 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($topicTypes as $type)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-center text-slate-500 font-medium">
                                {{ ($topicTypes->currentPage() - 1) * $topicTypes->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $type->name }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $type->description ?: '<span class="text-slate-300 italic">Không có mô tả</span>' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('topic-types.edit', $type->id) }}"
                                        class="text-amber-500 hover:text-amber-600 bg-amber-50 hover:bg-amber-100 p-2 rounded transition"
                                        title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    @php
                                        $hasTopics = \DB::table('topics')->where('topic_type_id', $type->id)->exists();
                                    @endphp

                                    <form action="{{ route('topic-types.destroy', $type->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="
                                        @if ($hasTopics) alert('Xin lỗi, không thể xoá vì loại chuyên đề này đang được sử dụng. Bạn phải đổi loại hoặc xoá các chuyên đề thuộc loại này trước.'); return false;
                                        @else
                                            return confirm('Bạn có chắc chắn muốn xóa loại chuyên đề này không?'); @endif
                                    ">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 p-2 rounded transition"
                                            title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 text-slate-300 block"></i>
                                Chưa có dữ liệu loại chuyên đề.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($topicTypes->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $topicTypes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
