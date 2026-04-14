<div class="animate-fade-in-up">
    <div class="border-b border-slate-200 pb-3 mb-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span class="w-6 h-6 inline-flex items-center justify-center bg-emerald-100 text-emerald-600 rounded-full text-sm font-bold">3</span>
            Nội dung Câu hỏi Đúng/Sai (TF)
        </h3>
        <p class="text-sm text-slate-500 mt-1">Soạn nội dung và chọn khẳng định dưới đây là Đúng hay Sai.</p>
    </div>

    <div class="space-y-6">
        {{-- KHUNG 1: ĐỀ BÀI (STEM) --}}
        <div>
            <div class="flex justify-between items-center mb-2">
                <label class="block text-sm font-semibold text-slate-700">Câu hỏi / Khẳng định <span class="text-rose-500">*</span></label>
                <button type="button" onclick="window.previewContent('editor-stem')" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                    <i class="fa-solid fa-eye"></i> preview
                </button>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <textarea id="editor-stem" name="stem" class="w-full border-0 focus:ring-0" rows="4">{!! old('stem', $data['stem'] ?? '') !!}</textarea>
            </div>
        </div>

        {{-- KHUNG 2: CHỌN ĐÁP ÁN ĐÚNG --}}
        {{-- KHUNG 2: ĐÁP ÁN ĐÚNG/SAI --}}
        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 inline-block pr-12">
            <label class="block text-sm font-semibold text-slate-700 mb-3">Đáp án <span
                    class="text-rose-500">*</span></label>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-emerald-50 transition">
                    <input type="radio" name="tf_answer" value="Đúng"
                        {{ old('tf_answer', $data['answer'] ?? '') == 'Đúng' ? 'checked' : '' }}
                        class="w-5 h-5 text-emerald-600 focus:ring-emerald-500 border-gray-300">
                    <span class="font-bold text-emerald-700">Đúng (True)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer p-2 rounded hover:bg-rose-50 transition">
                    <input type="radio" name="tf_answer" value="Sai"
                        {{ old('tf_answer', $data['answer'] ?? '') == 'Sai' ? 'checked' : '' }}
                        class="w-5 h-5 text-rose-600 focus:ring-rose-500 border-gray-300">
                    <span class="font-bold text-rose-700">Sai (False)</span>
                </label>
            </div>
        </div>

        {{-- KHUNG 4: LỜI GIẢI (EXPLANATION) --}}
        <div>
            <div class="flex justify-between items-end mb-2">
                <label class="block text-sm font-semibold text-slate-700">Lời giải chi tiết</label>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="flex justify-between items-center mb-2 bg-slate-50 border-b border-slate-200 px-4 py-2">
                    <label class="block text-sm font-semibold text-slate-700">Giải thích tại sao Đúng/Sai</label>
                    <button type="button" onclick="window.previewContent('editor-explanation')" class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                        <i class="fa-solid fa-eye"></i> Preview
                    </button>
                </div>
                <textarea id="editor-explanation" name="explanation" class="w-full border-0 focus:ring-0 p-3" rows="3">{!! old('explanation', $data['explanation'] ?? '') !!}</textarea>
            </div>
        </div>
    </div>
</div>