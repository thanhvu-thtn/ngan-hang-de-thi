<div class="animate-fade-in-up">
    <div class="border-b border-slate-200 pb-3 mb-6">
        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
            <span
                class="w-6 h-6 inline-flex items-center justify-center bg-blue-100 text-blue-600 rounded-full text-sm font-bold">3</span>
            Nội dung Trắc nghiệm (Nhiều lựa chọn)
        </h3>
        <p class="text-sm text-slate-500 mt-1">Soạn đề bài, lời giải và tích chọn vào 1 đáp án đúng nhất.</p>
    </div>

    <div class="space-y-6">
        {{-- KHUNG 1: ĐỀ BÀI (STEM) --}}
        <div>
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
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <textarea id="editor-stem" name="stem" class="w-full border-0 focus:ring-0" rows="4">{!! old('stem', $data['stem'] ?? '') !!}</textarea>
            </div>
        </div>

        {{-- KHUNG 2: CÁC PHƯƠNG ÁN (CHOICES) --}}
        <div>
            <div class="flex justify-between items-end mb-3">
                <label class="block text-sm font-semibold text-slate-700">Các phương án lựa chọn <span
                        class="text-rose-500">*</span></label>
            </div>

            <div class="space-y-5">
                @foreach ($data['choices'] as $index => $choice)
                    <div
                        class="p-5 border border-slate-200 rounded-xl bg-slate-50 shadow-sm relative transition-all hover:border-blue-300">
                        <input type="hidden" name="choices[{{ $index }}][id]" value="{{ $choice['id'] ?? '' }}">

                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-semibold text-slate-700">
                                Nội dung phương án {{ $index + 1 }} <span class="text-rose-500">*</span>
                            </label>

                            {{-- NÚT XEM TRƯỚC ĐỀ BÀI --}}
                            <button type="button" onclick="window.previewContent('editor-choice-{{ $index }}')"
                                class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                                <i class="fa-solid fa-eye"></i> Preview
                            </button>
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm mb-4 bg-white">
                            <textarea id="editor-choice-{{ $index }}" name="choices[{{ $index }}][content]"
                                class="w-full border-0 focus:ring-0 editor-choice" rows="3">{!! old("choices.{$index}.content", $choice['content'] ?? '') !!}</textarea>
                        </div>

                        <div
                            class="flex items-center gap-6 bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm inline-flex">

                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="correct_choice" value="{{ $index }}"
                                    {{ old('correct_choice') == $index || $choice['is_correct'] ? 'checked' : '' }}
                                    class="w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500 cursor-pointer">
                                <span
                                    class="text-sm font-bold text-slate-600 group-hover:text-green-600 transition-colors">Là
                                    đáp án đúng</span>
                            </label>

                            <div class="w-px h-6 bg-slate-200"></div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-slate-600" for="ratio-{{ $index }}">Tỷ lệ
                                    chiều ngang màn hình (%):</label>
                                <input type="number" id="ratio-{{ $index }}"
                                    name="choices[{{ $index }}][ratio]"
                                    value="{{ old("choices.{$index}.ratio", $choice['ratio'] ?? ($choice['is_correct'] ? 1 : 0)) }}"
                                    class="w-20 text-sm border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-1.5 text-center font-semibold text-slate-700"
                                    min="0" max="100" step="0.1">
                            </div>
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-slate-600" for="order-{{ $index }}">Thứ tự
                                    :</label>
                                <input type="number" id="order-{{ $index }}"
                                    name="choices[{{ $index }}][order]"
                                    value="{{ old("choices.{$index}.order", $choice['order'] ?? 0) }}"
                                    class="w-20 text-sm border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 py-1.5 text-center font-semibold text-slate-700"
                                    min="0" step="1">
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- KHUNG 3: CẤU HÌNH HIỂN THỊ (LAYOUT) - MỚI THÊM --}}
        <div>
            <div class="flex justify-between items-end mb-2">
                <label for="layout_id" class="block text-sm font-semibold text-slate-700">
                    Cấu hình hiển thị đáp án (Layout) <span class="text-rose-500">*</span>
                </label>
            </div>
            <select id="layout_id" name="layout_id" 
                class="w-full md:w-1/2 border-slate-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-2 font-medium text-slate-700 transition-colors cursor-pointer">
                <option value="">-- Chọn cách bố trí (Ví dụ: 1x4, 2x2) --</option>
                {{-- Giả định bác truyền biến $layouts từ Controller xuống --}}
                @isset($layouts)
                    @foreach ($layouts as $layout)
                        <option value="{{ $layout->id }}" 
                            {{ old('layout_id', $data['layout_id'] ?? '') == $layout->id ? 'selected' : '' }}>
                            {{ $layout->name }} ({{ $layout->code }})
                        </option>
                    @endforeach
                @endisset
            </select>
        </div>

        {{-- KHUNG 4: LỜI GIẢI (EXPLANATION) --}}
        <div>
            <div class="flex justify-between items-end mb-2">
                <label class="block text-sm font-semibold text-slate-700">Lời giải chi tiết</label>
            </div>
            <div class="border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="flex justify-between items-center mb-2 bg-slate-50 border-b border-slate-200 px-4 py-2">
                    <label class="block text-sm font-semibold text-slate-700">
                        Lời giải / Hướng dẫn chấm
                    </label>
                    {{-- NÚT XEM TRƯỚC LỜI GIẢI --}}
                    <button type="button" onclick="window.previewContent('editor-explanation')"
                        class="text-xs px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition border border-blue-200 flex items-center gap-1 shadow-sm font-medium">
                        <i class="fa-solid fa-eye"></i> Preview
                    </button>
                </div>
                <textarea id="editor-explanation" name="explanation" class="w-full border-0 focus:ring-0 p-3" rows="4">{!! old('explanation', $data['explanation'] ?? '') !!}</textarea>
            </div>
        </div>
    </div>
</div>