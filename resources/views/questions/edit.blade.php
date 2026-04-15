@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-5xl">

        {{-- Header --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-pen-to-square text-white text-sm"></i>
                    </div>
                    Chỉnh sửa câu hỏi
                </h2>
                <p class="text-slate-500 text-sm mt-1">Cập nhật thông tin, mục tiêu và nội dung câu hỏi.</p>
            </div>
            <button type="button" onclick="window.history.back();"
                class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 font-medium transition shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </button>
        </div>

        <form action="{{ route('questions.update', $question->id) }}" method="POST" id="question-edit-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="type_code" value="{{ $question->questionType->code }}">

            {{-- =========================================================
             KHỐI 1: THÔNG TIN CƠ BẢN (Gồm cả Thẩm định)
             ========================================================= --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500"></i> Thông tin cơ bản
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Tóm tắt nội dung --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tóm tắt nội dung (Name) <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $question->name) }}"
                            class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2.5 px-[3px]"
                            placeholder="Nhập tóm tắt nội dung...">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Mã định danh --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mã định danh (Tag Name) <span
                                class="text-rose-500">*</span></label>
                        <input type="text" name="tag_name" id="tag_name_input"
                            value="{{ old('tag_name', $question->tag_name) }}"
                            class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 font-semibold text-blue-700 bg-blue-50/50 text-sm py-2.5 px-3"
                            placeholder="Mã định danh không được để trống...">
                        <p id="tag_name_warning" class="text-sm text-rose-600 mt-1 hidden font-medium">Mã này đã tồn tại. Do
                            đó, không thể lưu câu hỏi với mã này. Xin vui lòng chọn mã khác!</p>
                    </div>

                    {{-- Mức độ nhận thức --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mức độ nhận thức <span
                                class="text-rose-500">*</span></label>
                        <select name="cognitive_level_id"
                            class="w-full border-slate-300 rounded-xl shadow-sm py-2.5 text-sm px-[3px]">
                            @foreach (\App\Models\CognitiveLevel::all() as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('cognitive_level_id', $question->cognitive_level_id) == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- TRẠNG THÁI (Phân quyền) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Trạng thái phê duyệt</label>
                        @can('tham-dinh-cau-hoi')
                            <select name="status"
                                class="w-full border-amber-300 bg-amber-50 rounded-lg focus:ring-amber-500 shadow-sm px-[5pt]">
                                <option value="0" {{ old('status', $question->status) == 0 ? 'selected' : '' }}>Chờ thẩm
                                    định</option>
                                <option value="1" {{ old('status', $question->status) == 1 ? 'selected' : '' }}>Đã duyệt
                                </option>
                                <option value="2" {{ old('status', $question->status) == 2 ? 'selected' : '' }}>Cần sửa
                                    lại</option>
                            </select>
                        @else
                            <div
                                class="py-2.5 px-4 bg-slate-100 border border-slate-200 rounded-xl text-slate-600 flex items-center gap-2">
                                @if ($question->status == 1)
                                    <i class="fa-solid fa-circle-check text-green-500"></i> Đã phê duyệt
                                @elseif($question->status == 2)
                                    <i class="fa-solid fa-circle-exclamation text-rose-500"></i> Cần soạn lại
                                @else
                                    <i class="fa-solid fa-clock text-amber-500"></i> Đang chờ duyệt
                                @endif
                            </div>
                        @endcan
                    </div>

                    {{-- ĐỘ KHÓ (Phân quyền) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Độ khó (0-100)</label>
                        @can('tham-dinh-cau-hoi')
                            <input type="number" step="0.1" name="difficulty_index"
                                value="{{ old('difficulty_index', $question->difficulty_index) }}"
                                class="w-full border-amber-300 bg-amber-50 rounded-lg shadow-sm px-[5pt]">
                        @else
                            <div class="py-2.5 px-4 bg-slate-100 border border-slate-200 rounded-xl text-slate-600">
                                {{ $question->difficulty_index ?? 'Chưa thiết lập' }}
                            </div>
                        @endcan
                    </div>
                </div>
                {{-- DÁN ĐOẠN NÀY VÀO ĐÂY: Thứ tự hiển thị (Nằm riêng 1 hàng, kích thước 1/4) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6 items-start">
                    <div class="md:col-span-4">
                        <label for="sort_order" class="block text-sm font-bold text-slate-700 mb-2">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" id="sort_order"
                            value="{{ old('sort_order', $question->sort_order ?? 0) }}" step="1"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 text-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                        @error('sort_order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>


            {{-- =========================================================
             KHỐI 2: MỤC TIÊU ĐÁNH GIÁ (ĐƯA LÊN TRÊN)
             ========================================================= --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-crosshairs text-rose-500"></i> Mục tiêu đánh giá <span
                        class="text-rose-500">*</span>
                </h3>

                @include('questions.partials.treeview', [
                    'treeByGrade' => $treeByGrade,
                    'inputName' => 'objective_ids[]',
                    'showCount' => false,
                    'selectedIds' => $selectedObjectiveIds,
                ])
                @error('objective_ids')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- =========================================================
             KHỐI 3: NỘI DUNG RIÊNG (Tải động)
             ========================================================= --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-emerald-500"></i> Nội dung câu hỏi
                </h3>

                @php
                    $typeFolder = match ($question->questionType->code) {
                        'ES' => 'essay',
                        'MC' => 'multiple_choice',
                        'TF' => 'true_false',
                        'SA' => 'short_answer',
                        default => strtolower($question->questionType->code),
                    };
                    $viewPath = "questions.{$typeFolder}.edit";
                @endphp

                @if (View::exists($viewPath))
                    @include($viewPath, ['question' => $question])
                @else
                    <div class="p-4 bg-rose-50 text-rose-600 rounded-xl border border-rose-200">
                        Giao diện chỉnh sửa cho loại {{ $question->questionType->name }} chưa được cấu hình.
                    </div>
                @endif
            </div>

            {{-- Nút điều hướng --}}
            <div class="mt-8 flex justify-end gap-3 sticky bottom-4">
                <a href="{{ route('questions.index') }}"
                    class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 font-semibold transition">Hủy
                    bỏ</a>
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold transition shadow-lg shadow-blue-200 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Cập nhật câu hỏi
                </button>
            </div>
        </form>
    </div>

    @include('questions.partials.treeview_js')
@endsection

@push('scripts')
    @include('partials.editor_script', ['selector' => '#editor-stem, #editor-explanation, .editor-choice'])
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tagInput = document.getElementById('tag_name_input');
            const warningMsg = document.getElementById('tag_name_warning');
            const originalTagName = "{{ $question->tag_name }}";

            tagInput.addEventListener('blur', function() {
                const val = this.value.trim();
                if (!val || val === originalTagName) {
                    warningMsg.classList.add('hidden');
                    return;
                };

                fetch(`/questions/check-tag-name?tag_name=${encodeURIComponent(val)}`)
                    .then(r => r.json())
                    .then(data => {
                        data.exists ? warningMsg.classList.remove('hidden') : warningMsg.classList.add(
                            'hidden');
                    });
            });
        });
    </script>
@endpush
