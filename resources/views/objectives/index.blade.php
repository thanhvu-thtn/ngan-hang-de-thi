
@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-7xl">

        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ request('back_url') ? urldecode(request('back_url')) : route('contents.index', ['grade' => $content->topic->grade, 'topic_id' => $content->topic_id]) }}"
                    class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 py-2 px-3 rounded-lg shadow-sm transition duration-150"
                    title="Quay lại danh sách Nội dung">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>

                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Yêu cầu cần đạt</h2>
                    <p class="text-indigo-600 text-sm font-medium mt-1">
                        Nội dung: {{ $content->name }}
                        <span class="text-gray-400 mx-2">|</span>
                        Chuyên đề: {{ $content->topic->name }}
                    </p>
                </div>
            </div>

            <a href="{{ route('objectives.create', ['content_id' => $content->id]) }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Thêm Yêu cầu
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-16">
                            STT
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-1/4">
                            Mã định danh
                        </th>
                        <th class="py-3 px-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Nội dung chi tiết
                        </th>
                        <th class="py-3 px-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider w-24">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($objectives as $index => $objective)
                        <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                            <td class="py-4 px-4 align-top text-sm font-medium text-gray-900">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-4 px-4 align-top text-sm text-gray-500">
                                <span class="inline-block bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-medium">
                                    {{ $objective->tag_name ?? 'Chưa có mã' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 align-top">
                                <div class="prose prose-sm max-w-none text-gray-700 format-katex">
                                    {!! $objective->description !!}
                                </div>
                            </td>
                            <td class="py-4 px-4 align-top text-right space-x-2 whitespace-nowrap">
                                <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <a href="{{ route('objectives.edit', $objective->id) }}"
                                        class="text-amber-500 hover:text-amber-700" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Xóa">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-500">
                                <i class="fa-solid fa-folder-open text-4xl mb-3 block opacity-20"></i>
                                Chưa có yêu cầu cần đạt nào cho nội dung này.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection