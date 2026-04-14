<div class="animate-fade-in-up">
    <div class="border-b border-slate-200 pb-3 mb-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-6 h-6 inline-flex items-center justify-center bg-purple-100 text-purple-600 rounded-full text-sm font-bold">3</span>
            Nội dung Câu hỏi Trả lời ngắn (SA)
        </h3>
        <p class="text-sm text-slate-500 mt-1">Nhập câu hỏi và đáp án chính xác mà học sinh cần điền.</p>
    </div>

    <div class="space-y-6">
        {{-- KHUNG 1: ĐỀ BÀI (STEM) --}}
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-semibold text-slate-700">Câu hỏi <span class="text-rose-500">*</span></label>
                <button type="button" onclick="window.previewContent('editor-stem')" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                    <i class="fa-solid fa-eye"></i> preview
                </button>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <textarea id="editor-stem" name="stem" class="w-full border-0 focus:ring-0" rows="4">{!! old('stem', $data['stem'] ?? '') !!}</textarea>
            </div>
        </div>

        {{-- KHUNG 2: ĐÁP ÁN ĐÚNG --}}
        {{-- KHUNG 2: ĐÁP ÁN (ANSWER) --}}
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Đáp án <span
                    class="text-rose-500">*</span></label>

            <input type="text" name="sa_answer" value="{{ old('sa_answer', $data['answer'] ?? '') }}" maxlength="4"
                class="w-full md:w-1/3 border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-800 text-lg py-2 px-[3px]"
                placeholder="VD: 45 hoặc -3,1" oninput="formatShortAnswer(this)">

            <p class="text-sm text-slate-500 mt-1">Lưu ý: Chỉ nhập số và dấu phẩy (,). Tối đa 4 ký tự.</p>
        </div>

        {{-- SCRIPT XỬ LÝ NHẬP LIỆU (Chặn các trường hợp nhập sai định dạng) --}}
        

        {{-- KHUNG 4: LỜI GIẢI (EXPLANATION) --}}
        <div>
            <div class="flex justify-between items-end mb-2">
                <label class="block text-sm font-semibold text-slate-700">Lời giải chi tiết</label>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="flex justify-between items-center mb-2 bg-slate-50 border-b border-slate-200 px-4 py-2">
                    <label class="block text-sm font-semibold text-slate-700">Lời giải / Hướng dẫn chấm</label>
                    <button type="button" onclick="window.previewContent('editor-explanation')" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                        <i class="fa-solid fa-eye"></i> Preview
                    </button>
                </div>
                <textarea id="editor-explanation" name="explanation" class="w-full border-0 focus:ring-0 p-3" rows="3">{!! old('explanation', $data['explanation'] ?? '') !!}</textarea>
            </div>
        </div>
    </div>
</div>