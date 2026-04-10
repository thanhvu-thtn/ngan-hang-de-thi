{{-- File: resources/views/questions/essay/edit.blade.php --}}

{{-- Stem (Đề bài) --}}
<div class="mb-6">
    <div class="flex justify-between items-center mb-2">
        <label class="block text-sm font-semibold text-slate-700">
            Câu hỏi <span class="text-rose-500">*</span>
        </label>
        {{-- NÚT XEM TRƯỚC ĐỀ BÀI (Đã được canh phải nhờ justify-between) --}}
        <button type="button" onclick="window.previewContent('editor-stem')"
            class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
            <i class="fa-solid fa-eye"></i> preview
        </button>
    </div>

    <textarea id="editor-stem" name="stem" class="w-full">{!! old('stem', $question->stem) !!}</textarea>
    @error('stem')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Explanation (Lời giải) --}}
<div>
    <div class="flex justify-between items-center mb-2">
        <label class="block text-sm font-semibold text-slate-700">
            Lời giải / Hướng dẫn chấm
        </label>
        {{-- NÚT XEM TRƯỚC LỜI GIẢI (Đã được canh phải nhờ justify-between) --}}
        <button type="button" onclick="window.previewContent('editor-explanation')"
            class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
            <i class="fa-solid fa-eye"></i> Preview
        </button>
    </div>

    <textarea id="editor-explanation" name="explanation" class="w-full">{!! old('explanation', optional($question->explanation)->content) !!}</textarea>
    @error('explanation')
        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
    @enderror
</div>
