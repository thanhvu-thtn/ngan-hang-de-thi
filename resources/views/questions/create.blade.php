@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    
    {{-- Progress Header & Nút thao tác --}}
    <div class="mb-8 border-b border-slate-200 pb-4 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-blue-600 rounded-lg shadow-lg">
                    <i class="fa-solid fa-pen-nib text-white text-sm"></i>
                </div>
                Soạn câu hỏi mới
            </h2>
            <div class="flex items-center gap-4 mt-4 text-sm font-medium">
                <div id="step-1-indicator" class="flex items-center gap-2 text-blue-600">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs">1</span>
                    Thiết lập & Mục tiêu
                </div>
                <div class="h-px w-8 bg-slate-300"></div>
                <div id="step-2-indicator" class="flex items-center gap-2 text-slate-400">
                    <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center text-xs">2</span>
                    Nội dung câu hỏi
                </div>
            </div>
        </div>

        {{-- KHOẢNG NÚT ĐIỀU HƯỚNG TRÊN CÙNG --}}
        <div class="flex items-center gap-3">
            {{-- Nút của Bước 2 (Ẩn mặc định) --}}
            <button type="button" id="btn-prev-step-top" class="hidden px-5 py-2.5 bg-white border border-slate-300 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition flex items-center gap-2 shadow-sm">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </button>
            <button type="submit" id="btn-submit-top" form="question-form" class="hidden px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Lưu câu hỏi
            </button>

            {{-- Nút của Bước 1 (Hiện mặc định) --}}
            <button type="button" id="btn-next-step-top" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2">
                Tiếp tục soạn nội dung <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </div>

    <form action="{{ route('questions.store') }}" method="POST" id="question-form">
        @csrf

        {{-- ================= BƯỚC 1: THAM SỐ & MỤC TIÊU ================= --}}
        <div id="step-1-container" class="space-y-6 animate-fade-in-down">
            
            {{-- Chọn Mức độ & Loại câu hỏi --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Loại câu hỏi <span class="text-rose-500">*</span></label>
                    <select name="question_type_id" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($questionTypes as $type)
                            <option value="{{ $type->id }}" {{ $type->code === 'ESSAY' ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mức độ nhận thức <span class="text-rose-500">*</span></label>
                    <select name="cognitive_level_id" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach($cognitiveLevels as $level)
                            <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- TreeView chọn Mục tiêu --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-10">
                <label class="block text-sm font-semibold text-slate-700 mb-4 flex items-center justify-between">
                    <span>Chọn Mục tiêu đánh giá (Yêu cầu cần đạt) <span class="text-rose-500">*</span></span>
                    <span class="text-xs font-normal text-slate-400 italic text-right">Nhấp vào mũi tên để mở rộng chuyên đề</span>
                </label>
                
                <div class="space-y-2 max-h-[500px] overflow-y-auto custom-scrollbar p-2 rounded-xl border border-slate-100 bg-slate-50/50">
                    @forelse($treeByGrade as $grade => $topics)
                        {{-- NODE KHỐI --}}
                        <div class="grade-node border border-transparent rounded-xl transition-all duration-300 mb-2">
                            <h3 class="grade-header font-bold text-slate-700 p-3 bg-white border border-slate-200 rounded-xl flex items-center gap-3 cursor-pointer hover:bg-blue-50 hover:border-blue-200 transition-colors">
                                <i class="fa-solid fa-caret-right text-slate-400 w-4 transition-transform duration-200"></i>
                                <i class="fa-solid fa-graduation-cap text-blue-500"></i>
                                Khối {{ $grade }}
                            </h3>

                            <div class="topic-list hidden space-y-4 p-4 ml-4 border-l-2 border-blue-100 mt-1">
                                @foreach ($topics as $topic)
                                    {{-- NODE CHUYÊN ĐỀ --}}
                                    <div class="topic-node border border-transparent rounded-xl transition-all duration-300">
                                        <div class="topic-header font-semibold text-slate-800 mb-2 flex items-center gap-2 cursor-pointer hover:text-blue-600">
                                            <i class="fa-solid fa-caret-right text-slate-300 w-3 transition-transform duration-200"></i>
                                            <span class="flex-1">{{ $topic->name }}</span>
                                        </div>

                                        <div class="content-list hidden space-y-3 ml-5 border-l border-slate-200 pl-4 mt-2">
                                            @foreach($topic->contents as $content)
                                                {{-- NODE NỘI DUNG --}}
                                                <div class="content-node border border-transparent rounded-lg transition-all duration-300">
                                                    <div class="content-toggle flex items-center gap-2 font-medium text-slate-600 text-sm py-1.5 cursor-pointer hover:text-blue-600">
                                                        <i class="fa-solid fa-caret-right text-slate-300 w-3 transition-transform duration-200"></i>
                                                        <i class="fa-regular fa-folder-open text-amber-400"></i> {{ $content->name }}
                                                    </div>

                                                    <div class="objective-list hidden space-y-2 mt-2 pl-4 pr-2 pb-2">
                                                        @foreach($content->objectives as $objective)
                                                            <label class="flex items-start gap-3 p-3 bg-white border border-slate-200 rounded-xl shadow-sm hover:border-blue-400 cursor-pointer transition-all duration-200 group">
                                                                <input type="checkbox" name="objective_ids[]" value="{{ $objective->id }}" class="obj-checkbox mt-1 w-5 h-5 text-blue-600 rounded focus:ring-blue-500 border-slate-300">
                                                                <div class="text-sm text-slate-700 leading-relaxed flex-1 group-hover:text-slate-900 math-content">
                                                                    {!! $objective->description !!}
                                                                </div>
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
            </div>
        </div>

        {{-- ================= BƯỚC 2: SOẠN THẢO NỘI DUNG ================= --}}
        <div id="step-2-container" class="hidden animate-fade-in-down space-y-6">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-10">
                
                {{-- KHU VỰC 1: NỘI DUNG CÂU HỎI --}}
                <div class="mb-8">
                    <div class="flex justify-between items-end mb-3">
                        <label class="block text-sm font-bold text-slate-700">Nội dung câu hỏi <span class="text-rose-500">*</span></label>
                        <button type="button" class="btn-preview-math px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 border border-indigo-200" data-target="editor-question">
                            <i class="fa-solid fa-eye"></i> Xem trước công thức
                        </button>
                    </div>

                    {{-- Trình soạn thảo --}}
                    <div id="container-editor-question">
                        <textarea name="question_text" id="editor-question" class="tinymce-instance"></textarea>
                    </div>

                    {{-- Vùng Xem trước (Mặc định ẩn) --}}
                    <div id="preview-editor-question" class="hidden relative border border-slate-300 rounded-xl bg-slate-50 min-h-[250px] p-6 shadow-inner">
                        <button type="button" class="btn-close-preview absolute top-3 right-3 px-3 py-1.5 bg-rose-100 text-rose-600 hover:bg-rose-200 rounded-lg text-sm font-bold transition-colors flex items-center gap-2" data-target="editor-question">
                            <i class="fa-solid fa-xmark"></i> Đóng lại
                        </button>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">Chế độ xem trước (Preview)</div>
                        <div class="preview-content text-slate-800 leading-relaxed math-content">
                            {{-- Nội dung render sẽ được JS nhét vào đây --}}
                        </div>
                    </div>
                </div>

                {{-- KHU VỰC 2: LỜI GIẢI CHI TIẾT --}}
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <label class="block text-sm font-bold text-slate-700">Lời giải chi tiết / Hướng dẫn chấm</label>
                        <button type="button" class="btn-preview-math px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2 border border-indigo-200" data-target="editor-explanation">
                            <i class="fa-solid fa-eye"></i> Xem trước công thức
                        </button>
                    </div>

                    {{-- Trình soạn thảo --}}
                    <div id="container-editor-explanation">
                        <textarea name="explanation" id="editor-explanation" class="tinymce-instance"></textarea>
                    </div>

                    {{-- Vùng Xem trước (Mặc định ẩn) --}}
                    <div id="preview-editor-explanation" class="hidden relative border border-slate-300 rounded-xl bg-slate-50 min-h-[250px] p-6 shadow-inner">
                        <button type="button" class="btn-close-preview absolute top-3 right-3 px-3 py-1.5 bg-rose-100 text-rose-600 hover:bg-rose-200 rounded-lg text-sm font-bold transition-colors flex items-center gap-2" data-target="editor-explanation">
                            <i class="fa-solid fa-xmark"></i> Đóng lại
                        </button>
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">Chế độ xem trước (Preview)</div>
                        <div class="preview-content text-slate-800 leading-relaxed math-content">
                            {{-- Nội dung render sẽ được JS nhét vào đây --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 6px; }
    
    /* Highlight Classes */
    .highlight-grade { border-color: #3b82f6 !important; background-color: #eff6ff !important; }
    .highlight-topic { color: #2563eb !important; font-weight: 700; }
    .highlight-content { color: #1d4ed8 !important; font-weight: 700; }
    
    /* State: Rotate Arrow */
    .is-open > i.fa-caret-right, 
    .is-open > .topic-header i.fa-caret-right,
    .is-open > .content-toggle i.fa-caret-right { transform: rotate(90deg); color: #3b82f6; }

    /* Checkbox highlight */
    label:has(input:checked) { background-color: #f0fdf4; border-color: #22c55e; box-shadow: 0 0 0 1px #22c55e; }

    .animate-fade-in-down { animation: fadeInDown 0.4s ease-out; }
    @keyframes fadeInDown { 0% { opacity: 0; transform: translateY(-15px); } 100% { opacity: 1; transform: translateY(0); } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================
    // 1. XỬ LÝ ĐÓNG/MỞ TREEVIEW & HIGHLIGHT
    // ==========================================
    function setupToggle(triggerSelector, targetSelector) {
        document.querySelectorAll(triggerSelector).forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                if (e.target.type === 'checkbox') return;
                const parent = this.parentElement;
                const target = parent.querySelector(targetSelector);
                if (target) {
                    const isOpen = !target.classList.contains('hidden');
                    target.classList.toggle('hidden');
                    this.classList.toggle('is-open', !isOpen);
                }
            });
        });
    }

    setupToggle('.grade-header', '.topic-list');
    setupToggle('.topic-header', '.content-list');
    setupToggle('.content-toggle', '.objective-list');

    const checkboxes = document.querySelectorAll('.obj-checkbox');
    function updateGenealogyHighlight() {
        document.querySelectorAll('.highlight-grade, .highlight-topic, .highlight-content').forEach(el => {
            el.classList.remove('highlight-grade', 'highlight-topic', 'highlight-content');
        });
        document.querySelectorAll('.obj-checkbox:checked').forEach(checkedBox => {
            const contentNode = checkedBox.closest('.content-node')?.querySelector('.content-toggle');
            if(contentNode) contentNode.classList.add('highlight-content');
            const topicNode = checkedBox.closest('.topic-node')?.querySelector('.topic-header');
            if(topicNode) topicNode.classList.add('highlight-topic');
            const gradeNode = checkedBox.closest('.grade-node');
            if(gradeNode) gradeNode.classList.add('highlight-grade');
        });
    }
    checkboxes.forEach(box => box.addEventListener('change', updateGenealogyHighlight));

    // ==========================================
    // 2. XỬ LÝ CHUYỂN BƯỚC & KHỞI TẠO TINYMCE
    // ==========================================
    const step1 = document.getElementById('step-1-container');
    const step2 = document.getElementById('step-2-container');
    const ind1 = document.getElementById('step-1-indicator');
    const ind2 = document.getElementById('step-2-indicator');
    
    const btnNextTop = document.getElementById('btn-next-step-top');
    const btnPrevTop = document.getElementById('btn-prev-step-top');
    const btnSubmitTop = document.getElementById('btn-submit-top');

    btnNextTop.addEventListener('click', () => {
        const selected = document.querySelectorAll('.obj-checkbox:checked').length;
        if(selected === 0) {
            alert('Vui lòng chọn ít nhất 1 Mục tiêu đánh giá!');
            return;
        }
        
        // Chuyển container (hiện Bước 2)
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        
        // Cập nhật thanh trạng thái & Đổi nút Header
        ind2.classList.remove('text-slate-400');
        ind2.classList.add('text-blue-600', 'font-bold');
        ind2.querySelector('span').classList.replace('bg-slate-200', 'bg-blue-600');
        ind2.querySelector('span').classList.replace('text-slate-500', 'text-white');
        btnNextTop.classList.add('hidden');
        btnPrevTop.classList.remove('hidden');
        btnSubmitTop.classList.remove('hidden');

        // KHỞI TẠO TINYMCE SAU KHI VÙNG CHỨA ĐÃ HIỂN THỊ
        if (window.tinymce && !window.tinymce.get('editor-question')) {
            window.tinymce.init({
                selector: '.tinymce-instance',
                license_key: 'gpl', // Giữ nguyên để qua mặt báo lỗi bản quyền
                height: 350,
                menubar: 'file edit view insert format tools table',
                plugins: ['lists', 'link', 'code', 'table', 'image'],
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table image | code',
                
                // === CẤU HÌNH OFFLINE BẮT BUỘC ===
                skin: false, // Tắt chế độ tự tìm file skin qua HTTP
                content_css: false, // Tắt chế độ tự tìm file css nội dung qua HTTP
                content_style: window.tinymceContentCss, // Bơm trực tiếp chuỗi CSS đã được Vite build từ app.js vào iframe
                // =================================

                promotion: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save(); 
                    });
                }
            });
        } // <--- MÌNH ĐÃ THÊM DẤU ĐÓNG NGOẶC CỦA IF Ở ĐÂY
    }); // <--- VÀ THÊM DẤU ĐÓNG NGOẶC CỦA ADD_EVENT_LISTENER Ở ĐÂY

    // Xử lý nút quay lại
    btnPrevTop.addEventListener('click', () => {
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        
        ind2.classList.add('text-slate-400');
        ind2.classList.remove('text-blue-600', 'font-bold');
        ind2.querySelector('span').classList.replace('bg-blue-600', 'bg-slate-200');
        ind2.querySelector('span').classList.replace('text-white', 'text-slate-500');
        btnNextTop.classList.remove('hidden');
        btnPrevTop.classList.add('hidden');
        btnSubmitTop.classList.add('hidden');
    });

    // ==========================================
    // 3. XỬ LÝ NÚT PREVIEW TOÁN HỌC (KATEX)
    // ==========================================
    document.querySelectorAll('.btn-preview-math').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const containerEditor = document.getElementById(`container-${targetId}`);
            const containerPreview = document.getElementById(`preview-${targetId}`);
            const contentDiv = containerPreview.querySelector('.preview-content');

            // Lấy HTML từ TinyMCE đắp vào khung Preview
            if (window.tinymce && window.tinymce.get(targetId)) {
                contentDiv.innerHTML = window.tinymce.get(targetId).getContent();
            }

            // Kích hoạt KaTeX quét qua khung Preview
            if (window.renderMathInElement) {
                window.renderMathInElement(contentDiv, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '\\[', right: '\\]', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false}
                    ],
                    throwOnError: false
                });
            }

            // Đảo trạng thái hiển thị
            containerEditor.classList.add('hidden');
            containerPreview.classList.remove('hidden');
            this.classList.add('hidden'); // Ẩn chính nút "Xem trước"
        });
    });

    // Xử lý nút Đóng Preview
    document.querySelectorAll('.btn-close-preview').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const containerEditor = document.getElementById(`container-${targetId}`);
            const containerPreview = document.getElementById(`preview-${targetId}`);
            const btnPreview = document.querySelector(`.btn-preview-math[data-target="${targetId}"]`);

            // Đảo ngược trạng thái hiển thị
            containerPreview.classList.add('hidden');
            containerEditor.classList.remove('hidden');
            btnPreview.classList.remove('hidden');
        });
    });
});
</script>
@endsection