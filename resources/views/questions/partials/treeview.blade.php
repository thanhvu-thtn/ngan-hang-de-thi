{{-- 
    Component TreeView Dùng Chung
    Tham số nhận vào:
    - $treeByGrade : Data cây mục tiêu (bắt buộc)
    - $inputName   : Tên input checkbox (VD: 'objective_ids[]' hoặc 'filter_objective_ids[]')
    - $showCount   : boolean, có hiển thị số lượng câu hỏi hay không
--}}

<div
    class="space-y-2 max-h-[500px] overflow-y-auto custom-scrollbar p-2 rounded-xl border border-slate-100 bg-slate-50/50 treeview-container">
    @forelse($treeByGrade as $grade => $topics)
        {{-- NODE KHỐI --}}
        <div class="grade-node border border-transparent rounded-xl transition-all duration-300 mb-2">
            <h3
                class="grade-header font-bold text-slate-700 p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors">
                <i class="fa-solid fa-caret-right text-slate-400 w-4 transition-transform duration-200"></i>
                <i class="fa-solid fa-graduation-cap text-blue-500"></i>
                Khối {{ $grade }}
            </h3>

            <div class="topic-list hidden space-y-4 p-4 ml-4 border-l-2 border-blue-100 mt-1">
                @foreach ($topics as $topic)
                    {{-- NODE CHUYÊN ĐỀ --}}
                    <div class="topic-node border border-transparent rounded-xl transition-all duration-300">
                        <div
                            class="topic-header font-semibold text-slate-800 mb-2 flex items-center gap-2 cursor-pointer hover:text-blue-600">
                            <i class="fa-solid fa-caret-right text-slate-300 w-3 transition-transform duration-200"></i>
                            <span class="flex-1">{{ $topic->name }}</span>
                        </div>

                        <div class="content-list hidden space-y-3 ml-5 border-l border-slate-200 pl-4 mt-2">
                            @foreach ($topic->contents as $content)
                                {{-- NODE NỘI DUNG --}}
                                <div
                                    class="content-node border border-transparent rounded-lg transition-all duration-300">
                                    <div
                                        class="content-toggle flex items-center gap-2 font-medium text-slate-600 text-sm py-1.5 cursor-pointer hover:text-blue-600">
                                        <i
                                            class="fa-solid fa-caret-right text-slate-300 w-3 transition-transform duration-200"></i>
                                        <i class="fa-regular fa-folder-open text-amber-400"></i>
                                        {{ $content->name }}
                                    </div>

                                    <div class="objective-list hidden space-y-2 mt-2 pl-4 pr-2 pb-2">
                                        @foreach ($content->objectives as $objective)
                                            <label
                                                class="flex items-start gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-blue-400 cursor-pointer transition-all duration-200 group">

                                                <input type="checkbox" name="{{ $inputName ?? 'objective_ids[]' }}"
                                                    value="{{ $objective->id }}"
                                                    class="obj-checkbox mt-1 w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-slate-300"
                                                    {{-- Thêm logic kiểm tra Checkbox này vào --}}
                                                    {{ in_array($objective->id, old(str_replace('[]', '', $inputName ?? 'objective_ids'), $selectedIds ?? [])) ? 'checked' : '' }}>

                                                <div
                                                    class="text-sm text-slate-700 leading-relaxed flex-1 group-hover:text-slate-900 math-content format-katex">
                                                    {!! $objective->description !!}
                                                </div>

                                                {{-- CHỈ HIỂN THỊ SỐ LƯỢNG KHI Ở MÀN HÌNH INDEX ($showCount = true) --}}
                                                @if (isset($showCount) && $showCount)
                                                    <span
                                                        class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-blue-800 bg-blue-100 rounded-full">
                                                        {{ $objective->questions_count ?? 0 }}
                                                    </span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-10 text-slate-400 italic">Không có dữ liệu chuyên đề.</div>
    @endforelse
</div>
