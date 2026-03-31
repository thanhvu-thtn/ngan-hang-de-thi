@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-4 py-6" x-data="{ showAddModal: false, showEditModal: false, editData: { id: '', name: '', code: '', expected_choices_count: '', url: '' } }">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 flex items-center">
                    <i class="fa-solid fa-clipboard-question text-blue-600 mr-3"></i>
                    Quản lý Loại câu hỏi
                </h1>
                <p class="text-slate-500 text-sm mt-1">Danh sách các định dạng câu hỏi trong hệ thống</p>
            </div>

            <button @click="showAddModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition duration-150 flex items-center">
                <i class="fa-solid fa-plus mr-2"></i> Thêm Loại câu hỏi
            </button>
        </div>

        {{-- Thông báo --}}


        {{-- Thông báo lỗi --}}
        @if ($errors->any())
            <div class="bg-rose-50 text-rose-600 border border-rose-200 p-4 rounded-md mb-4 shadow-sm">
                <div class="flex items-center mb-2 font-bold text-rose-700">
                    <i class="fa-solid fa-triangle-exclamation mr-2 text-lg"></i>
                    <span>Thao tác thất bại!</span>
                </div>
                <ul class="list-disc list-inside text-sm ml-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Bảng dữ liệu --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-16 text-center">STT</th>
                        <th scope="col" class="px-6 py-4">Tên loại câu hỏi</th>
                        <th scope="col" class="px-6 py-4">Mã (Code)</th>
                        <th scope="col" class="px-6 py-4 text-center">Số lựa chọn chuẩn</th>
                        <th scope="col" class="px-6 py-4 w-32 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($questionTypes as $index => $type)
                        <tr class="hover:bg-slate-50 transition duration-150">
                            <td class="px-6 py-4 text-center text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $type->name }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="bg-slate-100 text-slate-600 px-2 py-1 rounded text-xs font-mono border border-slate-200">
                                    {{ $type->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-blue-600">
                                {{ $type->expected_choices_count }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center space-x-3">
                                    <button
                                        @click="showEditModal = true; editData = { id: '{{ $type->id }}', name: '{{ $type->name }}', code: '{{ $type->code }}', expected_choices_count: '{{ $type->expected_choices_count }}', url: '{{ route('question-types.update', $type->id) }}' }"
                                        class="text-amber-500 hover:text-amber-700 transition" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('question-types.destroy', $type->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 transition"
                                            title="Xóa">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-500">Chưa có loại câu hỏi nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MODAL THÊM MỚI --}}
        <div x-show="showAddModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-50" @click="showAddModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[101]">
                    <form action="{{ route('question-types.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Thêm Loại câu hỏi</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tên loại câu hỏi <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="name" required
                                    class="w-full rounded-md border-slate-300 border px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Mã (Code) <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="code" required
                                    class="w-full rounded-md border-slate-300 border px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Số lượng lựa chọn chuẩn <span
                                        class="text-rose-500">*</span></label>
                                <input type="number" name="expected_choices_count" min="1" value="4" required
                                    class="w-full rounded-md border-slate-300 border px-3 py-2">
                                <p class="text-xs text-slate-500 mt-1">VD: Trắc nghiệm = 4, Trả lời ngắn = 1.</p>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 sm:ml-3">Lưu
                                lại</button>
                            <button type="button" @click="showAddModal = false"
                                class="mt-3 w-full sm:w-auto px-4 py-2 bg-white text-slate-700 font-medium rounded-md border hover:bg-slate-50 sm:mt-0 sm:ml-3">Hủy
                                bỏ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL SỬA --}}
        <div x-show="showEditModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-slate-900 bg-opacity-50" @click="showEditModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-[101]">
                    <form x-bind:action="editData.url" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-bold text-slate-900 mb-4">Sửa Loại câu hỏi</h3>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Tên loại câu hỏi <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="editData.name" required
                                    class="w-full rounded-md border-slate-300 border px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Mã (Code) <span
                                        class="text-rose-500">*</span></label>
                                <input type="text" name="code" x-model="editData.code" required
                                    class="w-full rounded-md border-slate-300 border px-3 py-2">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Số lượng lựa chọn chuẩn <span
                                        class="text-rose-500">*</span></label>
                                <input type="number" name="expected_choices_count"
                                    x-model="editData.expected_choices_count" min="1" required
                                    class="w-full rounded-md border-slate-300 border px-3 py-2">
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 sm:ml-3">Cập
                                nhật</button>
                            <button type="button" @click="showEditModal = false"
                                class="mt-3 w-full sm:w-auto px-4 py-2 bg-white text-slate-700 font-medium rounded-md border hover:bg-slate-50 sm:mt-0 sm:ml-3">Hủy
                                bỏ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
