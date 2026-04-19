@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-5xl" x-data="{
        questionType: '{{ old('question_type_code', 'MC') }}',
        tagName: '{{ old('tag_name', '') }}',
        isCheckingTag: false
    }">

        <form action="{{ route('questions.store') }}" method="POST" id="question-form">
            @csrf
            @if (isset($sharedContextId))
                <input type="hidden" name="shared_context_id" value="{{ $sharedContextId }}">
            @endif
            {{-- PHẦN 1: CẤU HÌNH CHUNG --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-gear text-blue-500"></i> Phần 1: Cấu hình chung
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mã định danh (Tag name) <span
                                class="text-rose-500">*</span></label>
                        <div class="relative">
                            <input type="text" id="tag_name_input" name="tag_name" x-model="tagName" required
                                class="w-full rounded-lg border-slate-200 focus:ring-blue-500 pr-10 pl-3"
                                placeholder="VL2B-PT-01">

                            <button type="button" id="clear_tag_btn"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 hidden transition-colors">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        </div>
                        <p id="tag_name_warning" class="text-xs text-rose-500 mt-1.5 font-medium hidden">
                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Mã định danh này đã tồn tại, vui lòng chọn
                            mã khác!
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tóm tắt nội dung</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-lg border-slate-200 p-3" placeholder="VD: Phương trình bậc hai">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Mức độ nhận thức</label>
                        <select name="cognitive_level_id" class="w-full rounded-lg border-slate-200">
                            @foreach ($cognitiveLevels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('cognitive_level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Loại câu hỏi</label>
                        <select name="question_type_code" x-model="questionType"
                            class="w-full rounded-lg border-blue-200 bg-blue-50 font-bold text-blue-700">
                            @foreach ($questionTypes as $type)
                                <option value="{{ $type->code }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- PHẦN 2: CHỌN MỤC TIÊU KIẾN THỨC (TREEVIEW) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-folder-tree text-indigo-500"></i> Phần 2: Chọn mục tiêu kiến thức <span
                        class="text-rose-500">*</span>
                </h3>

                <div class="border border-slate-200 rounded-xl overflow-hidden bg-slate-50/50">
                    @include('questions.partials.treeview', [
                        'treeByGrade' => $treeByGrade,
                        'inputName' => 'objective_ids[]',
                        'showCount' => false,
                        'selectedObjectives' => old('objective_ids', []), // Hỗ trợ giữ lại tick mục tiêu (cần file treeview hỗ trợ)
                    ])
                </div>
                <p class="text-[12px] text-slate-500 mt-3 italic">
                    <i class="fa-solid fa-circle-info mr-1"></i> Bạn có thể tích chọn một hoặc nhiều mục tiêu cho câu hỏi
                    này. Bấm vào mũi tên để mở rộng chi nhánh.
                </p>
            </div>

            {{-- PHẦN 3: NỘI DUNG CÂU HỎI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-file-lines text-emerald-500"></i> Phần 3: Nội dung câu hỏi
                </h3>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-semibold text-slate-700">Đề bài / Phần dẫn <span
                                class="text-rose-500">*</span></label>
                        <div class="flex gap-2">
                            <button type="button" onclick="window.activateEditor('editor-stem')" class="btn-tiny-edit">
                                <i class="fa-solid fa-pen-nib"></i> Edit TinyMCE
                            </button>
                            <button type="button" onclick="window.previewContent('editor-stem')" class="btn-preview">
                                <i class="fa-solid fa-eye"></i> Preview
                            </button>
                        </div>
                    </div>
                    <textarea id="editor-stem" name="stem" rows="4" class="w-full rounded-xl border-slate-200 p-3"
                        placeholder="Nhập câu hỏi...">{!! old('stem') !!}</textarea>
                </div>

                <hr class="my-6 border-slate-100">

                {{-- CÁC LỰA CHỌN BIẾN ĐỔI --}}

                <div x-show="questionType === 'MC'" class="space-y-6">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-slate-600 italic uppercase">Các phương án trả lời:</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @for ($i = 0; $i < 4; $i++)
                            <div
                                class="p-4 border border-slate-100 rounded-xl bg-slate-50/50 hover:bg-white hover:shadow-sm transition-all">
                                <div class="flex justify-between items-center mb-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="correct_choice" value="{{ $i }}"
                                            {{ old('correct_choice', '0') == $i ? 'checked' : '' }}
                                            class="w-4 h-4 text-blue-600">
                                        <span class="text-sm font-bold text-slate-600">Phương án {{ $i + 1 }}</span>
                                    </label>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            onclick="window.activateEditor('choice-{{ $i }}')"
                                            class="btn-tiny-edit">
                                            <i class="fa-solid fa-pen-nib"></i> Edit TinyMCE
                                        </button>
                                        <button type="button"
                                            onclick="window.previewContent('choice-{{ $i }}')"
                                            class="btn-preview">
                                            <i class="fa-solid fa-eye"></i> Preview
                                        </button>
                                    </div>
                                </div>
                                <textarea id="choice-{{ $i }}" name="choices[{{ $i }}][content]" rows="2"
                                    class="w-full border-slate-200 rounded-lg text-sm p-3" placeholder="Nội dung phương án...">{!! old("choices.{$i}.content") !!}</textarea>

                                <div class="mt-3 flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-400">Chiều dài tương đối của đáp án
                                        (ratio):</span>
                                    <input type="number" name="choices[{{ $i }}][ratio]" step="0.1"
                                        min="0" max="1" value="{{ old("choices.{$i}.ratio", 1) }}"
                                        class="w-16 text-xs font-bold p-1 border-slate-200 rounded text-center">
                                </div>
                            </div>
                        @endfor
                    </div>

                    {{-- KHUNG CẤU HÌNH HIỂN THỊ (LAYOUT) CHỈ DÀNH CHO MC --}}
                    <div class="p-5 border border-blue-100 bg-blue-50/30 rounded-xl">
                        <div class="flex justify-between items-end mb-2">
                            <label for="layout_id" class="block text-sm font-bold text-slate-700">
                                Cấu hình hiển thị đáp án (Layout) <span class="text-rose-500">*</span>
                            </label>
                        </div>

                        @php
                            $defaultLayoutId = isset($layouts) && $layouts->isNotEmpty() ? $layouts->last()->id : '';
                        @endphp

                        <select id="layout_id" name="layout_id"
                            class="w-full md:w-1/2 border-slate-200 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2 text-sm font-medium text-slate-700 transition-colors cursor-pointer">
                            <option value="">-- Chọn cách bố trí (Ví dụ: 1x4, 2x2) --</option>
                            @isset($layouts)
                                @foreach ($layouts as $layout)
                                    <option value="{{ $layout->id }}"
                                        {{ old('layout_id', $defaultLayoutId) == $layout->id ? 'selected' : '' }}>
                                        {{ $layout->name }}
                                    </option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                </div>

                <div x-show="questionType === 'TF'" class="p-5 bg-emerald-50 border border-emerald-100 rounded-xl">
                    <label class="block text-sm font-bold text-emerald-800 mb-3 uppercase tracking-wider">Đáp án
                        đúng:</label>
                    <div class="flex gap-8">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="tf_answer" value="Đúng"
                                {{ old('tf_answer', 'Đúng') == 'Đúng' ? 'checked' : '' }}
                                class="w-5 h-5 text-emerald-600">
                            <span class="font-bold text-emerald-700 group-hover:text-emerald-900">Đúng (True)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="tf_answer" value="Sai"
                                {{ old('tf_answer', 'Sai') == 'Sai' ? 'checked' : '' }} class="w-5 h-5 text-rose-600">
                            <span class="font-bold text-rose-700 group-hover:text-rose-900">Sai (False)</span>
                        </label>
                    </div>
                </div>

                <div x-show="questionType === 'SA'"
                    class="p-5 bg-purple-50 border border-purple-100 rounded-xl text-center">
                    <label class="block text-sm font-bold text-purple-800 mb-3 uppercase tracking-wider">Đáp án số (Tối đa
                        4 ký tự):</label>
                    <input type="text" name="sa_answer" maxlength="4" value="{{ old('sa_answer') }}"
                        oninput="this.value = this.value.replace(/[^0-9,-]/g, '');"
                        class="w-40 text-2xl font-bold text-center border-purple-200 rounded-xl focus:ring-purple-500 shadow-sm"
                        placeholder="VD: -2,5">
                </div>

                <div x-show="questionType === 'ES'"
                    class="p-8 border-2 border-dashed border-slate-200 rounded-xl text-center">
                    <i class="fa-solid fa-pen-clip text-slate-300 text-3xl mb-2"></i>
                    <p class="text-sm text-slate-400 italic">Loại tự luận: Không yêu cầu nhập phương án trả lời.</p>
                </div>
            </div>

            {{-- PHẦN 4: LỜI GIẢI --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb text-amber-500"></i> Phần 4: Lời giải chi tiết
                    </h3>
                    <div class="flex gap-2">
                        <button type="button" onclick="window.activateEditor('editor-explanation')"
                            class="btn-tiny-edit">
                            <i class="fa-solid fa-pen-nib"></i> Edit TinyMCE
                        </button>
                        <button type="button" onclick="window.previewContent('editor-explanation')" class="btn-preview">
                            <i class="fa-solid fa-eye"></i> Preview
                        </button>
                    </div>
                </div>
                <textarea id="editor-explanation" name="explanation" rows="4" class="w-full rounded-xl border-slate-200 p-3"
                    placeholder="Nhập lời giải...">{!! old('explanation') !!}</textarea>
            </div>

            {{-- NÚT LƯU --}}
            <div class="flex justify-end gap-4">
                <a href="{{ route('questions.index') }}"
                    class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-600 font-bold hover:bg-slate-50 transition">Hủy
                    bỏ</a>
                <button type="submit"
                    class="px-10 py-2.5 rounded-xl bg-blue-600 text-white font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu câu hỏi
                </button>
            </div>
        </form>
    </div>

    {{-- MODAL PREVIEW --}}



@endsection

@push('styles')
    <style>
        .btn-tiny-edit {
            @apply text-[11px] px-3 py-1 bg-purple-50 text-purple-600 hover:bg-purple-100 rounded-md border border-purple-200 transition flex items-center gap-1 font-bold;
        }

        .btn-preview {
            @apply text-[11px] px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md border border-blue-200 transition flex items-center gap-1 font-bold;
        }
    </style>
@endpush

@push('scripts')
    @include('partials.editor_script-01')
    @include('questions.partials.treeview_js')
    <script>
        // ==========================================
        // XỬ LÝ NÚT CLEAR VÀ AJAX CHECK TAG_NAME
        // ==========================================
        document.addEventListener("DOMContentLoaded", function() {
            const tagInput = document.getElementById('tag_name_input');
            const clearBtn = document.getElementById('clear_tag_btn');
            const warningMsg = document.getElementById('tag_name_warning');

            // Hàm ẩn/hiện nút X dựa trên độ dài text
            const toggleClearBtn = () => {
                if (tagInput.value.length > 0) {
                    clearBtn.classList.remove('hidden');
                } else {
                    clearBtn.classList.add('hidden');
                }
            };

            // Khởi chạy lần đầu lúc load trang
            toggleClearBtn();

            // Khi người dùng gõ
            tagInput.addEventListener('input', () => {
                toggleClearBtn();
                warningMsg.classList.add('hidden'); // Đang gõ thì ẩn cảnh báo cũ đi
                tagInput.classList.remove('border-rose-500', 'ring-rose-500'); // Xóa viền đỏ khi sửa
            });

            // Bấm nút X xóa sạch
            clearBtn.addEventListener('click', () => {
                tagInput.value = '';
                // Nếu dùng AlpineJS thì phải dispatch event để nó update x-model
                tagInput.dispatchEvent(new Event('input'));
                toggleClearBtn();
                warningMsg.classList.add('hidden');
                tagInput.classList.remove('border-rose-500', 'ring-rose-500');
                tagInput.focus(); // Đưa con trỏ chuột trở lại ô
            });

            // Khi rời khỏi ô (blur) -> Gọi AJAX
            tagInput.addEventListener('blur', function() {
                const val = this.value.trim();
                if (!val) return; // Nếu trống thì không gọi API

                fetch(`/questions/check-tag-name?tag_name=${encodeURIComponent(val)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            warningMsg.classList.remove('hidden'); // Hiện đỏ
                            tagInput.classList.add('border-rose-500', 'ring-rose-500'); // Tô viền đỏ
                        } else {
                            warningMsg.classList.add('hidden'); // Ẩn cảnh báo
                            tagInput.classList.remove('border-rose-500', 'ring-rose-500'); // Bỏ viền đỏ
                        }
                    })
                    .catch(err => console.error('Lỗi khi kiểm tra tag_name:', err));
            });
        });
    </script>
@endpush
