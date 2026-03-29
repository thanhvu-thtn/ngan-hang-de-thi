@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Quản lý Nội dung</h2>
                <p class="text-sm text-gray-500 mt-1">Danh sách các nội dung bài học thuộc chuyên đề</p>
            </div>
            <a href="{{ route('contents.create', request()->query()) }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Thêm Nội dung
            </a>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm mb-6"
                role="alert">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6" role="alert">
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('contents.index') }}" method="GET"
                class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-center">

                <div class="lg:col-span-2">
                    <select name="grade" id="filterGrade"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-2">
                        <option value="">-- Tất cả Khối --</option>
                        @foreach ([10, 11, 12] as $g)
                            <option value="{{ $g }}" {{ request('grade') == $g ? 'selected' : '' }}>Khối
                                {{ $g }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <select name="topic_type_id" id="filterTopicType"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-2">
                        <option value="">-- Loại chuyên đề --</option>
                        @foreach ($topicTypes as $type)
                            <option value="{{ $type->id }}"
                                {{ request('topic_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-3">
                    <select name="topic_id" id="filterTopic"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-2">
                        <option value="">-- Tất cả Chuyên đề --</option>
                        @foreach ($topics as $topic)
                            <option value="{{ $topic->id }}" data-grade="{{ $topic->grade }}"
                                data-type="{{ $topic->topic_type_id }}"
                                {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-2">
                    <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tên nội dung..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm py-2">
                </div>

                <div class="lg:col-span-2 flex gap-2">
                    <button type="submit"
                        class="w-full bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 px-3 rounded-md shadow-sm transition duration-150 flex items-center justify-center">
                        <i class="fa-solid fa-magnifying-glass mr-1.5"></i> Lọc
                    </button>
                    <a href="{{ route('contents.index') }}"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium py-2 px-3 rounded-md shadow-sm transition duration-150 flex items-center justify-center text-center">
                        Xóa
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full table-auto">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-xs leading-normal border-b border-gray-200">
                            <th class="py-3 px-6 text-left font-semibold">Tên nội dung</th>
                            <th class="py-3 px-6 text-left font-semibold">Thuộc Chuyên đề</th>
                            <th class="py-3 px-6 text-center font-semibold">Thứ tự</th>
                            <th class="py-3 px-6 text-center font-semibold">Số tiết</th>
                            <th class="py-3 px-6 text-center font-semibold">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse($contents as $content)
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition duration-150">
                                <td class="py-3 px-6 text-left font-medium">
                                    <a href="{{ route('objectives.index', [
                                        'grade' => request('grade'),
                                        'topic_id' => $content->topic_id,
                                        'content_id' => $content->id,
                                    ]) }}"
                                        class="text-slate-700 hover:text-indigo-600 hover:underline transition duration-150 block">
                                        {{ $content->name }}
                                    </a>
                                </td>
                                <td class="py-3 px-6 text-left">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold">
                                        {{ $content->topic->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center">{{ $content->order }}</td>
                                <td class="py-3 px-6 text-center">{{ $content->periods }}</td>
                                <td class="py-3 px-6 text-center">
                                    <div class="flex item-center justify-center space-x-3">

                                        <a href="{{ route('contents.edit', ['content' => $content->id] + request()->query()) }}"
                                            class="text-amber-500 hover:text-amber-700" title="Sửa">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        @if ($content->objectives()->exists())
                                            <button type="button"
                                                onclick="alert('Nội dung này còn yêu cầu cần đạt, không thể xoá. Nếu muốn xoá nội dung này, bạn phải xoá hết yêu cầu cần đạt của nó trước.');"
                                                class="text-gray-300 cursor-not-allowed"
                                                title="Không thể xóa do có dữ liệu con">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        @else
                                            <form
                                                action="{{ route('contents.destroy', ['content' => $content->id] + request()->query()) }}"
                                                method="POST"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn xóa nội dung này?');"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700"
                                                    title="Xóa">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-gray-500">Chưa có nội dung nào được tìm
                                    thấy.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $contents->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gradeSelect = document.getElementById('filterGrade');
            const typeSelect = document.getElementById('filterTopicType');
            const topicSelect = document.getElementById('filterTopic');
            const originalOptions = Array.from(topicSelect.options).slice(1); // Bỏ qua option "-- Tất cả --"

            function filterTopics() {
                const selectedGrade = gradeSelect.value;
                const selectedType = typeSelect.value;

                // Ẩn tất cả option trước
                originalOptions.forEach(option => option.style.display = 'none');

                // Lọc và hiển thị lại các option thỏa mãn
                let hasVisibleOption = false;
                originalOptions.forEach(option => {
                    const matchGrade = selectedGrade === "" || option.getAttribute('data-grade') ===
                        selectedGrade;
                    const matchType = selectedType === "" || option.getAttribute('data-type') ===
                        selectedType;

                    if (matchGrade && matchType) {
                        option.style.display = '';
                        hasVisibleOption = true;
                    } else {
                        // Nếu option đang bị chọn mà lại bị ẩn do bộ lọc, thì reset value của select
                        if (option.selected) {
                            topicSelect.value = "";
                        }
                    }
                });
            }

            // Lắng nghe sự kiện thay đổi
            gradeSelect.addEventListener('change', filterTopics);
            typeSelect.addEventListener('change', filterTopics);

            // Chạy lần đầu tiên khi load trang để đồng bộ trạng thái
            filterTopics();
        });
    </script>
@endsection
