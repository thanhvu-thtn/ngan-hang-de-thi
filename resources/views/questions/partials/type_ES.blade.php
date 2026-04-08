<div class="animate-fade-in-up">
    <div class="border-b border-slate-200 pb-3 mb-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-6 h-6 inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-sm font-bold">3</span> 
            Nội dung câu hỏi Tự luận
        </h3>
        <p class="text-sm text-slate-500 mt-1">Soạn thảo đề bài và lời giải chi tiết. Dùng $$ cho công thức hiển thị độc lập, hoặc $ cho công thức trong dòng.</p>
    </div>

    <div class="space-y-6">
        {{-- KHUNG 1: ĐỀ BÀI (STEM) --}}
        <div>
            <div class="flex justify-between items-end mb-2">
                <label class="block text-sm font-semibold text-slate-700">
                    Đề bài (Nội dung câu hỏi) <span class="text-rose-500">*</span>
                </label>
                {{-- NÚT XEM TRƯỚC ĐỀ BÀI --}}
                <button type="button" onclick="window.previewContent('editor-stem')" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                    <i class="fa-solid fa-eye"></i> Xem trước Toán học
                </button>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <textarea id="editor-stem" name="stem" class="w-full border-0 focus:ring-0" rows="6" placeholder="Nhập nội dung đề bài tại đây...">{!! old('stem') !!}</textarea>
            </div>
            @error('stem')
                <p class="text-sm text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>

        {{-- KHUNG 2: LỜI GIẢI (EXPLANATION) --}}
        <div>
            <div class="flex justify-between items-end mb-2">
                <label class="block text-sm font-semibold text-slate-700">
                    Lời giải chi tiết / Hướng dẫn chấm
                </label>
                {{-- NÚT XEM TRƯỚC LỜI GIẢI --}}
                <button type="button" onclick="window.previewContent('editor-explanation')" class="text-xs px-3 py-1.5 bg-slate-50 text-slate-600 hover:bg-slate-100 rounded-lg transition border border-slate-200 flex items-center gap-1 shadow-sm font-medium">
                    <i class="fa-solid fa-eye"></i> Xem trước Toán học
                </button>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <textarea id="editor-explanation" name="explanation" class="w-full border-0 focus:ring-0" rows="6" placeholder="Nhập lời giải hoặc hướng dẫn chấm (không bắt buộc)...">{!! old('explanation') !!}</textarea>
            </div>
            @error('explanation')
                <p class="text-sm text-red-500 mt-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

