@extends('layouts.main')

@section('content')
    <div class="container mx-auto p-6 max-w-5xl">

        {{-- Header --}}
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-plus text-white text-sm"></i>
                    </div>
                    Biên soạn câu hỏi mới
                </h2>
                <p class="text-slate-500 text-sm mt-1">Môn học: <span
                        class="font-bold text-blue-600">{{ $user->subject->name ?? 'Chưa phân môn' }}</span></p>
            </div>
            <div class="text-right">
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Người soạn</span>
                <p class="text-sm font-semibold text-slate-700">{{ $user->name }}</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('questions.store') }}" method="POST" id="question-form" enctype="multipart/form-data">
            @csrf
            @if (isset($sharedContextId))
                <input type="hidden" name="shared_context_id" value="{{ $sharedContextId }}">
            @endif

            {{-- BƯỚC 1: CẤU HÌNH NHANH --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6">

                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2"><span
                        class="w-6 h-6 inline-flex items-center justify-center bg-slate-100 rounded-full text-sm mr-2">1</span>
                    Cấu hình chung</h3>
                {{-- 1. TÓM TẮT VÀ MÃ ĐỊNH DANH --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    {{-- Tóm tắt nội dung --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Tóm tắt nội dung (Name) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full border border-slate-300 rounded-md bg-transparent px-[5px] py-2 text-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors pr-8"
                            placeholder="Tóm tắt ngắn gọn nội dung câu hỏi')">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mã định danh (Dùng Str::uuid() của Laravel để tạo sẵn UUID) --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">
                            Mã định danh (Tag Name) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="tag_name_input" name="tag_name"
                                value="{{ old('tag_name', (string) \Illuminate\Support\Str::uuid()) }}"
                                class="w-full border border-slate-300 rounded-md bg-transparent px-[5px] py-2 text-slate-800 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors pr-8">
                            {{-- Nút X (Clear) --}}
                            <button type="button" id="clear_tag_btn"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-rose-500 transition-colors hidden focus:outline-none">
                                <i class="fa-solid fa-circle-xmark text-lg"></i>
                            </button>
                        </div>
                        {{-- Cảnh báo AJAX --}}
                        <p id="tag_name_warning" class="text-sm text-rose-600 mt-1 hidden font-medium">
                            <i class="fa-solid fa-triangle-exclamation"></i> Mã định danh này đã có trong CSDL, yêu cầu bạn
                            gõ
                            lại nếu không sẽ không lưu câu hỏi được!
                        </p>
                        @error('tag_name')
                            <p class="text-sm text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6 items-start">

                    {{-- Mức độ --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Mức độ nhận thức <span
                                class="text-rose-500">*</span></label>
                        <select name="cognitive_level_id"
                            class="form-select w-full rounded-xl border-slate-300 focus:ring-blue-500" required>
                            <option value="">-- Chọn mức độ --</option>
                            @foreach ($cognitiveLevels as $level)
                                <option value="{{ $level->id }}"
                                    {{ old('cognitive_level_id') == $level->id ? 'selected' : '' }}>
                                    {{ $level->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Loại câu hỏi --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Loại câu hỏi <span
                                class="text-rose-500">*</span></label>
                        <select name="type_code" id="question_type_select"
                            class="form-select w-full rounded-xl border-slate-300 focus:ring-blue-500" required>
                            <option value="">-- Chọn loại câu hỏi --</option>
                            @foreach ($questionTypes as $type)
                                <option value="{{ $type->code }}"
                                    {{ old('type_code') == $type->code ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Thứ tự hiển thị --}}
                    <div class="md:col-span-1">
                        <label for="sort_order" class="block text-sm font-bold text-slate-700 mb-2">Thứ tự</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                            step="1"
                            class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 text-slate-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
                        @error('sort_order')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>



            {{-- BƯỚC 2: CHỌN MỤC TIÊU (TREEVIEW FULL BỀ NGANG) --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2"><span
                        class="w-6 h-6 inline-flex items-center justify-center bg-slate-100 rounded-full text-sm mr-2">2</span>
                    Chọn mục tiêu kiến thức <span class="text-rose-500">*</span></h3>

                <div class="border rounded-xl overflow-hidden bg-slate-50/50">
                    @include('questions.partials.treeview', [
                        'treeByGrade' => $treeByGrade,
                        'inputName' => 'objective_ids[]',
                        'showCount' => false,
                    ])
                </div>
                <p class="text-[12px] text-slate-500 mt-3 italic">* Bạn có thể tích chọn một hoặc nhiều mục tiêu cho câu hỏi
                    này. Bấm vào mũi tên để mở rộng chi nhánh.</p>
            </div>

            {{-- BƯỚC 3: KHUNG CHỨA NỘI DUNG (Load AJAX) --}}
            <div id="dynamic-form-container" class="mb-6">
                <div class="bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-12 text-center text-slate-400">
                    <i class="fa-solid fa-wand-magic-sparkles text-4xl mb-4 text-slate-200"></i>
                    <p class="text-base text-slate-600">Hãy chọn <b>Loại câu hỏi</b> ở bước 1 để hiển thị khung soạn thảo
                        nội dung.</p>
                </div>
            </div>

            {{-- NÚT THAO TÁC --}}
            {{-- ==========================================
                 KHU VỰC NÚT BẤM LƯU CÂU HỎI
                 ========================================== --}}
            <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('questions.index') }}"
                    class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 font-semibold transition shadow-sm">
                    Hủy bỏ
                </a>
                <button type="submit" id="btn-submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-bold transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    Lưu vào ngân hàng
                </button>
            </div>
        </form>
    </div>

    {{-- Script xử lý Treeview --}}
    @include('questions.partials.treeview_js')

    {{-- Script AJAX xử lý Form động --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('question_type_select');
            const container = document.getElementById('dynamic-form-container');
            const submitBtn = document.getElementById('btn-submit');

            function loadPartialForm(typeCode) {
                if (!typeCode) {
                    container.innerHTML =
                        `<div class="bg-slate-50 border border-dashed border-slate-300 rounded-2xl p-12 text-center text-slate-400"><i class="fa-solid fa-wand-magic-sparkles text-4xl mb-4 text-slate-200"></i><p class="text-base text-slate-600">Hãy chọn <b>Loại câu hỏi</b> ở bước 1 để hiển thị khung soạn thảo nội dung.</p></div>`;
                    submitBtn.classList.add('hidden');
                    return;
                }

                container.innerHTML =
                    `<div class="bg-white p-12 rounded-2xl border border-slate-200 text-center shadow-sm"><i class="fas fa-spinner fa-spin text-blue-500 text-4xl mb-3"></i><p class="text-slate-500">Đang tải biểu mẫu...</p></div>`;

                fetch(`/questions/get-partial/${typeCode}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.text();
                    })
                    .then(html => {
                        container.innerHTML = html;
                        submitBtn.classList.remove('hidden');

                        // 1. KÍCH HOẠT TINYMCE ĐỒNG BỘ
                        if (window.initEduBankEditor) {
                            window.initEduBankEditor('#editor-stem, #editor-explanation, .editor-choice');
                        }

                        // 2. RENDER TOÁN HỌC (Nếu đề bài đang tải lại từ validation lỗi)
                        if (typeof renderMathInElement === "function") {
                            renderMathInElement(container, {
                                delimiters: [{
                                        left: '$$',
                                        right: '$$',
                                        display: true
                                    },
                                    {
                                        left: '$',
                                        right: '$',
                                        display: false
                                    },
                                    {
                                        left: '\\(',
                                        right: '\\)',
                                        display: false
                                    },
                                    {
                                        left: '\\[',
                                        right: '\\]',
                                        display: true
                                    }
                                ]
                            });
                        }
                    })
                    .catch(error => {
                        container.innerHTML =
                            `<div class="p-8 bg-red-50 text-red-600 rounded-2xl border border-red-200 text-center"><i class="fa-solid fa-triangle-exclamation text-3xl mb-2"></i><p>Chưa có giao diện cho loại câu hỏi này.</p></div>`;
                        submitBtn.classList.add('hidden');
                    });
            }

            // ĐOẠN MỚI: CHÉP VÀO ĐÂY
            let currentTypeCode = typeSelect.value;

            typeSelect.addEventListener('change', function() {
                const newTypeCode = this.value;

                if (currentTypeCode && currentTypeCode !== newTypeCode) {
                    const isConfirm = confirm(
                        'Bạn có chắc chắn muốn đổi loại câu hỏi? Toàn bộ nội dung bạn vừa soạn thảo ở bên dưới sẽ bị xóa sạch.'
                    );

                    if (!isConfirm) {
                        this.value = currentTypeCode;
                        return;
                    }
                }

                currentTypeCode = newTypeCode;
                loadPartialForm(newTypeCode);
            });

            // Tự động load nếu có old value (khi bị lỗi validate)
            if (typeSelect.value) {
                loadPartialForm(typeSelect.value);
            }
        });

        // ==========================================
        // XỬ LÝ NÚT CLEAR VÀ AJAX CHECK TAG_NAME
        // ==========================================
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
        });

        // Bấm nút X xóa sạch
        clearBtn.addEventListener('click', () => {
            tagInput.value = '';
            toggleClearBtn();
            warningMsg.classList.add('hidden');
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
    </script>
    <script>
        function formatShortAnswer(input) {
            let val = input.value;

            // 1. Tự động chuyển tất cả dấu chấm (.) thành phẩy (,)
            val = val.replace(/\./g, ',');

            // 2. Loại bỏ mọi ký tự lạ (chỉ giữ số, phẩy, trừ)
            val = val.replace(/[^0-9,-]/g, '');

            // 3. Dấu trừ (-) CHỈ được phép nằm ở vị trí đầu tiên
            val = val.replace(/(?!^)-/g, '');

            // 4. BẮT BUỘC TRƯỚC DẤU PHẨY PHẢI CÓ SỐ
            // a. Nếu dấu phẩy đứng ngay đầu tiên -> xóa nó
            val = val.replace(/^,/, '');
            // b. Nếu dấu phẩy đứng ngay sau dấu trừ -> giữ lại dấu trừ, xóa dấu phẩy
            val = val.replace(/^-,/, '-');

            // 5. Chỉ cho phép tối đa 1 dấu phẩy (,)
            let parts = val.split(',');
            if (parts.length > 2) {
                val = parts[0] + ',' + parts.slice(1).join('');
            }

            input.value = val;
        }
    </script>
    @include('partials.editor_script', ['selector' => '#editor-stem, #editor-explanation, .editor-choice'])
@endsection
