@extends('layouts.main')

@section('title', 'Quản lý Môn học')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-book text-blue-600 mr-2"></i>Quản lý Môn
                    học</h2>
                <p class="text-slate-500 text-sm mt-1">Danh sách các môn học trong hệ thống</p>
            </div>
            <a href="{{ route('subjects.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Thêm môn học
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 w-20 text-center">ID</th>
                        <th class="px-6 py-4">Tên Môn Học</th>
                        <th class="px-6 py-4 w-48">Ngày tạo</th>
                        <th class="px-6 py-4 w-40 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subjects as $subject)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-center text-slate-500 font-medium">{{ $subject->id }}</td>
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $subject->name }}</td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $subject->created_at ? $subject->created_at->format('d/m/Y') : '' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('subjects.edit', $subject->id) }}"
                                        class="text-amber-500 hover:text-amber-600 bg-amber-50 hover:bg-amber-100 p-2 rounded transition"
                                        title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    @php
                                        $hasTopics = \DB::table('topics')->where('subject_id', $subject->id)->exists();
                                    @endphp

                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="
                @if ($hasTopics) alert('Xin lỗi không thể xoá vì môn học này còn có các chuyên đề. Nếu bạn muốn xoá môn học này thì phải xoá hết các chuyên đề của nó.'); return false;
                @else
                    return confirm('Bạn có chắc chắn muốn xóa môn học này không?'); @endif
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
                                Chưa có dữ liệu môn học.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($subjects->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $subjects->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
