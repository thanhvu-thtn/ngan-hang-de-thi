@extends('layouts.main')

@section('title', 'Quản lý Chuyên đề')

@section('content')
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-book-open text-blue-600 mr-2"></i>Quản lý
                    Chuyên đề</h2>
                <p class="text-slate-500 text-sm mt-1">Danh sách chuyên đề theo môn học và khối lớp</p>
            </div>
            <a href="{{ route('topics.create', array_merge(request()->only(['subject_id', 'topic_type_id', 'grade']), ['back_url' => urlencode(request()->fullUrl())])) }}"
                class="px-4 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-plus"></i> Thêm chuyên đề
            </a>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 mb-6">
            <form action="{{ route('topics.index') }}" method="GET" class="flex flex-wrap items-end gap-4">

                <div class="flex-1 min-w-[200px]">
                    <label for="keyword" class="block text-sm font-medium text-slate-700 mb-2">Tên chuyên đề</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
                        </div>
                        <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}"
                            class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            placeholder="Nhập từ khóa tìm kiếm...">
                    </div>
                </div>

                <div class="w-40">
                    <label for="subject_id" class="block text-sm font-medium text-slate-700 mb-2">Môn học</label>
                    <select name="subject_id" id="subject_id"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                        <option value="">-- Tất cả --</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-40">
                    <label for="grade" class="block text-sm font-medium text-slate-700 mb-2">Khối lớp</label>
                    <select name="grade" id="grade"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                        <option value="">-- Tất cả --</option>
                        <option value="10" {{ request('grade') == '10' ? 'selected' : '' }}>Khối 10</option>
                        <option value="11" {{ request('grade') == '11' ? 'selected' : '' }}>Khối 11</option>
                        <option value="12" {{ request('grade') == '12' ? 'selected' : '' }}>Khối 12</option>
                    </select>
                </div>

                <div class="w-40">
                    <label for="topic_type_id" class="block text-sm font-medium text-slate-700 mb-2">Loại chuyên đề</label>
                    <select name="topic_type_id" id="topic_type_id"
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                        <option value="">-- Tất cả --</option>
                        @foreach ($topicTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('topic_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium transition shadow-sm flex items-center gap-2 h-[42px]">
                        <i class="fa-solid fa-filter"></i> Lọc
                    </button>

                    @if (request()->hasAny(['keyword', 'subject_id', 'grade', 'topic_type_id']) &&
                            (request('keyword') || request('subject_id') || request('grade') || request('topic_type_id')))
                        <a href="{{ route('topics.index') }}"
                            class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg font-medium transition shadow-sm flex items-center gap-2 h-[42px]">
                            <i class="fa-solid fa-rotate-right"></i> Bỏ lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-4 w-16 text-center">STT</th>
                        <th class="px-4 py-4 w-16 text-center">Khối</th>
                        <th class="px-4 py-4 w-1/3">Tên Chuyên đề</th>
                        <th class="px-4 py-4">Môn học</th>
                        <th class="px-4 py-4">Loại chuyên đề</th>
                        <th class="px-4 py-4 w-20 text-center">Số tiết</th>
                        <th class="px-4 py-4 w-32 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($topics as $topic)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-4 py-4 text-center text-slate-500 font-medium">
                                {{ ($topics->currentPage() - 1) * $topics->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-4 text-center font-bold text-blue-600">{{ $topic->grade }}</td>

                            <td class="px-4 py-4 font-bold text-slate-800">
                                <a href="{{ route('contents.index', [
                                    'grade' => $topic->grade,
                                    'topic_type_id' => $topic->topic_type_id,
                                    'topic_id' => $topic->id,
                                    'back_url' => urlencode(request()->fullUrl()),
                                ]) }}"
                                    class="hover:text-indigo-600 hover:underline transition duration-150 block">
                                    <span class="text-slate-400 font-normal mr-1">[{{ $topic->order }}]</span>
                                    {{ $topic->name }}
                                </a>
                            </td>

                            <td class="px-4 py-4">
                                <span
                                    class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded-md text-xs font-semibold">{{ $topic->subject->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-4">
                                <span
                                    class="bg-slate-100 text-slate-700 px-2 py-1 rounded-md text-xs">{{ $topic->topicType->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-4 py-4 text-center font-medium">{{ $topic->total_periods }}</td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('topics.edit', ['topic' => $topic->id, 'back_url' => urlencode(request()->fullUrl())]) }}"
                                        class="text-amber-500 hover:text-amber-600 bg-amber-50 hover:bg-amber-100 p-1.5 rounded transition"
                                        title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>

                                    @php
                                        $hasContents = \DB::table('contents')->where('topic_id', $topic->id)->exists();
                                    @endphp

                                    <form action="{{ route('topics.destroy', $topic->id) }}" method="POST" class="inline"
                                        onsubmit="
                                        @if ($hasContents) alert('Xin lỗi, không thể xoá vì chuyên đề này đang chứa các nội dung bài học. Vui lòng xoá nội dung trước.'); return false;
                                        @else
                                            return confirm('Bạn có chắc chắn muốn xóa chuyên đề này không?'); @endif
                                    ">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 p-1.5 rounded transition"
                                            title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                <i class="fa-solid fa-filter-circle-xmark text-4xl mb-3 text-slate-300 block"></i>
                                Không tìm thấy chuyên đề nào phù hợp với bộ lọc.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if ($topics->hasPages())
                <div class="p-4 border-t border-slate-200">
                    {{ $topics->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
