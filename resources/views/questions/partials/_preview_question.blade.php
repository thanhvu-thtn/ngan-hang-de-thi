<div class="border border-slate-100 bg-slate-50 rounded-xl p-5 relative mt-4">
    {{-- Nhãn Loại Câu hỏi (Trên cùng góc phải) --}}
    <div
        class="absolute -top-3 -right-3 {{ empty($q['type']) ? 'bg-orange-500' : 'bg-blue-500' }} text-white text-xs font-bold px-3 py-1 rounded-lg shadow">
        Loại: {{ empty($q['type']) ? 'Chưa rõ' : strtoupper($q['type']) }}
    </div>

    {{-- KHỐI 1: THÔNG TIN CƠ BẢN (Hiển thị dạng Badge) --}}
    <div class="flex flex-wrap gap-2 mb-4 items-center">
        <span class="text-slate-800 font-bold">Câu {{ $index }}:</span>

        {{-- Type (Hiển thị thêm lỗi ở đây vì nhãn góc phải quá ngắn không chứa hết chữ) --}}
        @if (empty($q['type']))
            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded border border-orange-300 font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Type: Nội dung này chưa được nhập hoặc không upload
                được
            </span>
        @endif

        {{-- Name --}}
        @if (!empty($q['name']))
            <span class="text-slate-600 font-medium">[{{ $q['name'] }}]</span>
        @else
            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded border border-orange-300 font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Name: Nội dung này chưa được nhập hoặc không upload
                được
            </span>
        @endif

        {{-- Tag --}}
        @if (!empty($q['tag_name']))
            <span class="bg-slate-200 text-slate-700 text-xs px-2 py-1 rounded">Tag: {{ $q['tag_name'] }}</span>
        @else
            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded border border-orange-300 font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Tag: Nội dung này chưa được nhập hoặc không upload
                được
            </span>
        @endif

        {{-- Objectives --}}
        @if (!empty($q['objectives']))
            @foreach ($q['objectives'] as $obj)
                <span class="bg-sky-100 text-sky-700 text-xs px-2 py-1 rounded border border-sky-200">
                    <i class="fa-solid fa-bullseye mr-1"></i>{{ $obj }}
                </span>
            @endforeach
        @else
            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded border border-orange-300 font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>Objectives: Nội dung này chưa được nhập hoặc không
                upload được
            </span>
        @endif

        {{-- Cognitive Level --}}
        @if (!empty($q['cognitive_level_tag']))
            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded border border-purple-200">
                <i class="fa-solid fa-brain mr-1"></i>{{ $q['cognitive_level_tag'] }}
            </span>
        @else
            <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded border border-orange-300 font-medium">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i>CognitiveLevel: Nội dung này chưa được nhập hoặc
                không upload được
            </span>
        @endif
    </div>

    {{-- KHỐI HIỂN THỊ LỖI KIỂM DUYỆT (Quyền hạn & Định dạng) --}}
    @if (empty($q['is_ready_to_save']))
        <div class="mb-4 space-y-2">
            @if (!empty($q['permission_errors']))
                <div class="bg-rose-50 text-rose-600 border border-rose-200 px-3 py-2 rounded text-sm">
                    <strong><i class="fa-solid fa-shield-halved mr-1"></i> Lỗi quyền hạn:</strong>
                    <ul class="list-disc ml-5 mt-1">
                        @foreach ($q['permission_errors'] as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (!empty($q['format_errors']))
                <div class="bg-amber-50 text-amber-600 border border-amber-200 px-3 py-2 rounded text-sm">
                    <strong><i class="fa-solid fa-triangle-exclamation mr-1"></i> Lỗi định dạng:</strong>
                    <ul class="list-disc ml-5 mt-1">
                        @foreach ($q['format_errors'] as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    {{-- KHỐI 2: NỘI DUNG CÂU HỎI (Stem) --}}
    <div class="mb-4 mt-2">
        @if (!empty($q['stem']))
            <div class="prose max-w-none text-slate-800 format-katex bg-white p-4 rounded-lg border border-slate-200">
                {!! $q['stem'] !!}
            </div>
        @else
            <div class="bg-orange-50 border-l-4 border-orange-500 p-3 rounded-r-lg">
                <div class="flex items-center text-orange-700 text-sm font-bold">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Nội dung câu hỏi (Stem): Nội dung này chưa
                    được nhập hoặc không upload được
                </div>
            </div>
        @endif
    </div>

    {{-- KHỐI 3: CÁC LỰA CHỌN (Choices) --}}
    <div class="mb-4">
        {{-- Nếu CÓ dữ liệu choices thì luôn luôn in ra, bất kể loại câu hỏi gì --}}
        @if (!empty($q['choices']))

            {{-- (Tùy chọn) Thêm một cảnh báo nhẹ nếu đây là câu Tự luận (ES) mà lại có choices --}}
            {{-- Nơi hiển thị các cảnh báo (Warnings) mà không chặn lưu --}}
            @if (!empty($q['format_warnings']))
                <div class="mb-4">
                    @foreach ($q['format_warnings'] as $warning)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-2 rounded-r-lg">
                            <div class="flex items-center text-yellow-700 text-sm">
                                <i class="fa-solid fa-circle-info mr-2"></i>
                                <strong>Lưu ý:</strong> {{ $warning }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach ($q['choices'] as $choice)
                    <div
                        class="flex items-start gap-3 p-3 rounded-lg border {{ isset($choice['is_correct']) && $choice['is_correct'] === true ? 'bg-emerald-50 border-emerald-300' : 'bg-white border-slate-200' }}">
                        <div class="mt-1">
                            @if (isset($choice['is_correct']) && $choice['is_correct'] === true)
                                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                            @elseif(isset($choice['is_correct']) && $choice['is_correct'] === false)
                                <i class="fa-regular fa-circle text-slate-300 text-lg"></i>
                            @else
                                <i class="fa-solid fa-circle-question text-slate-400 text-lg"></i>
                            @endif
                        </div>
                        <div class="prose max-w-none text-sm text-slate-700 format-katex flex-1">
                            {!! $choice['content'] ?? '' !!}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Nếu KHÔNG CÓ choices, thì CHỈ báo lỗi với các loại câu hỏi Trắc nghiệm (MC, TF, SA) --}}
        @elseif(!empty($q['type']) && in_array(strtoupper($q['type']), ['MC', 'TF', 'SA']))
            <div class="bg-orange-50 border-l-4 border-orange-500 p-3 rounded-r-lg">
                <div class="flex items-center text-orange-700 text-sm font-bold">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Các lựa chọn đáp án (Choices): Nội dung này
                    chưa được nhập hoặc không upload được
                </div>
            </div>
        @endif
    </div>

    {{-- KHỐI 4: LỜI GIẢI / HƯỚNG DẪN (Explanation) --}}
    <div class="mt-4 pt-4 border-t border-slate-200">
        <div class="text-xs font-bold text-slate-600 uppercase mb-2 flex items-center gap-1">
            <i class="fa-solid fa-lightbulb"></i> Lời giải / Hướng dẫn:
        </div>
        @if (!empty($q['explanation']))
            <div
                class="prose max-w-none text-sm text-slate-600 bg-amber-50 p-3 rounded-lg border border-amber-100 format-katex">
                {!! $q['explanation'] !!}
            </div>
        @else
            <div class="bg-orange-50 border-l-4 border-orange-500 p-3 rounded-r-lg">
                <div class="flex items-center text-orange-700 text-sm font-bold">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i> Lời giải (Explanation): Nội dung này chưa được
                    nhập hoặc không upload được
                </div>
            </div>
        @endif
    </div>
</div>

{{-- KHỐI 5: TỔNG KẾT TRẠNG THÁI LƯU --}}
    <div class="mt-5">
        @if (!empty($q['is_ready_to_save']))
            {{-- Vượt qua cả 2 cửa -> Báo Xanh --}}
            <div class="bg-emerald-50 border border-emerald-200 border-l-4 border-l-emerald-500 p-3 rounded shadow-sm">
                <div class="flex items-center text-emerald-700 text-sm font-bold">
                    <i class="fa-solid fa-circle-check text-lg mr-2"></i> 
                    Trạng thái: Hợp lệ. Câu hỏi này sẽ được lưu vào ngân hàng!
                </div>
            </div>
        @else
            {{-- Rớt 1 trong 2 cửa -> Báo Đỏ --}}
            <div class="bg-rose-50 border border-rose-200 border-l-4 border-l-rose-500 p-3 rounded shadow-sm">
                <div class="flex items-center text-rose-700 text-sm font-bold">
                    <i class="fa-solid fa-circle-xmark text-lg mr-2"></i> 
                    Trạng thái: Có lỗi. Câu hỏi này sẽ KHÔNG được ghi vào ngân hàng!
                </div>
                <div class="text-xs text-rose-600 mt-1 ml-7">
                    * Vui lòng xem lại các thông báo báo lỗi (quyền hạn hoặc định dạng) ở phía trên để chỉnh sửa lại file Word.
                </div>
            </div>
        @endif
    </div>
