{{-- 
    Bọc toàn bộ nội dung vào thẻ FORM. 
    Dùng POST và trỏ tới route storeSetup đã khai báo trong web.php 
--}}
<form action="{{ route('questions.storeSetup') }}" method="POST" id="question-setup-form">
    @csrf

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">

        {{-- HEADER & NÚT ĐI TIẾP --}}
        <div class="flex justify-between items-center mb-8 pb-4 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-800 uppercase">
                1: THIẾT LẬP CÂU HỎI
            </h3>
            
            {{-- QUAN TRỌNG: Đổi type="button" thành type="submit" để form có thể gửi đi --}}
            <button type="submit" id="btn-next-step"
                class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                <span>Đi tiếp tới soạn nội dung</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

        {{-- PHẦN 1: TÓM TẮT & TỪ KHÓA/MÃ CÂU HỎI --}}
        <div class="space-y-6 mb-8">
            <div>
                <label class="block text-base font-semibold text-slate-800 mb-2">
                    Tóm tắt nội dung câu hỏi <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 text-slate-700"
                    placeholder="VD: Cho m, F tính a...">
                @error('name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-base font-semibold text-slate-800 mb-2">
                    Từ khoá (Tags) / Mã câu hỏi <span class="text-rose-500">*</span>
                </label>

                <div class="relative">
                    <input type="text" id="tag_name_input" name="tag_name" required
                        value="{{ old('tag_name', \Illuminate\Support\Str::uuid()->toString()) }}"
                        class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 pr-10 text-slate-700"
                        placeholder="Nhập mã câu hỏi...">

                    <button type="button"
                        onclick="document.getElementById('tag_name_input').value = ''; document.getElementById('tag_name_input').focus();"
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-rose-400 hover:text-rose-600 transition focus:outline-none"
                        title="Xóa mã">
                        <i class="fa-solid fa-circle-xmark text-lg"></i>
                    </button>
                </div>
                @error('tag_name') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror

                <p class="text-sm text-slate-500 mt-2 italic">
                    Hệ thống tự động tạo mã ngẫu nhiên (UUID) để đảm bảo không trùng lặp. Bạn có thể xóa để nhập mã riêng nếu muốn.
                </p>
            </div>
        </div>

        {{-- PHẦN 2: COMBOBOX (LOẠI CÂU HỎI & MỨC ĐỘ NHẬN THỨC) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
            <div>
                <label class="block text-base font-semibold text-slate-800 mb-2">
                    Loại câu hỏi <span class="text-rose-500">*</span>
                </label>
                <select name="type" required
                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 text-slate-700 cursor-pointer">
                    <option value="">-- Chọn loại câu hỏi --</option>
                    @foreach ($questionTypes as $type)
                        {{-- Dùng $type->id để khớp với validate 'exists:question_types,id' ở Controller --}}
                        <option value="{{ $type->id }}" {{ old('type') == $type->id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                @error('type') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-base font-semibold text-slate-800 mb-2">
                    Mức độ nhận thức <span class="text-rose-500">*</span>
                </label>
                <select name="level" required
                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-3 text-slate-700 cursor-pointer">
                    <option value="">-- Chọn mức độ nhận thức --</option>
                    @foreach ($cognitiveLevels as $level)
                        <option value="{{ $level->id }}" {{ old('level') == $level->id ? 'selected' : '' }}>
                            {{ $level->name }}
                        </option>
                    @endforeach
                </select>
                @error('level') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <hr class="border-slate-100 mb-8">

        {{-- PHẦN 3: MỤC TIÊU ĐÁNH GIÁ (TREEVIEW) --}}
        <div>
            <label class="block text-base font-semibold text-slate-800 mb-2">
                Chọn Mục tiêu đánh giá <span class="text-rose-500">*</span>
            </label>
            <p class="text-sm text-slate-500 mb-4 italic">Tick chọn các mục tiêu mà câu hỏi này hướng tới.</p>

            @error('objective_ids') <p class="text-rose-500 text-sm mb-2 font-semibold">{{ $message }}</p> @enderror

            @include('questions.partials.treeview', [
                'treeByGrade' => $treeByGrade,
                'showCount' => false,
                'inputName' => 'objective_ids[]',
            ])
        </div>

    </div>
</form>

{{-- Script hỗ trợ để người dùng không bấm nút nhiều lần khi đang xử lý --}}
<script>
    document.getElementById('question-setup-form').onsubmit = function() {
        const btn = document.getElementById('btn-next-step');
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Đang khởi tạo...';
    };
</script>