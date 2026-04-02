@extends('layouts.main') {{-- Hoặc layout chính của bạn --}}

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">
    
    {{-- THANH TIẾN TRÌNH --}}
    <div class="mb-8">
        <div class="flex items-center gap-4 text-sm font-medium text-slate-500">
            <span class="flex items-center gap-2 text-emerald-600"><i class="fa-solid fa-circle-check"></i> Bước 1: Thiết lập</span>
            <i class="fa-solid fa-chevron-right text-xs"></i>
            <span class="flex items-center gap-2 text-blue-600 underline underline-offset-8 decoration-2 font-bold">Bước 2: Soạn nội dung Tự luận</span>
        </div>
    </div>

    {{-- TÓM TẮT THÔNG TIN BƯỚC 1 --}}
    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 flex flex-wrap gap-6 text-sm">
        <div><span class="text-slate-500">Tên:</span> <strong class="text-slate-700">{{ $setupData['name'] ?? '' }}</strong></div>
        <div><span class="text-slate-500">Mã/Tag:</span> <code class="bg-slate-200 px-1.5 py-0.5 rounded text-blue-700 font-bold">{{ $setupData['tag_name'] ?? '' }}</code></div>
    </div>

    {{-- FORM SOẠN THẢO --}}
    <form action="{{ route('questions.es.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-8">
            
            {{-- PHẦN ĐỀ BÀI (STEM) --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-blue-500"></i> Nội dung đề bài (Câu hỏi) <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="previewContent('editor-stem')" 
                        class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-100 transition font-bold flex items-center gap-2 border border-indigo-200">
                        <i class="fa-solid fa-eye"></i> Xem trước công thức
                    </button>
                </div>
                
                <textarea name="stem" id="editor-stem" rows="10" required>{{ old('stem') }}</textarea>
                @error('stem') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- PHẦN HƯỚNG DẪN CHẤM / ĐÁP ÁN MẪU --}}
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
                <div class="flex justify-between items-center mb-4">
                    <label class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-regular fa-lightbulb text-amber-500"></i> Hướng dẫn chấm / Đáp án gợi ý
                    </label>
                    <button type="button" onclick="previewContent('editor-explanation')" 
                        class="text-sm bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl hover:bg-indigo-100 transition font-bold flex items-center gap-2 border border-indigo-200">
                        <i class="fa-solid fa-eye"></i> Xem trước công thức
                    </button>
                </div>
                
                <textarea name="explanation" id="editor-explanation" rows="6">{{ old('explanation') }}</textarea>
                @error('explanation') <p class="text-rose-500 text-xs mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- NÚT ĐIỀU HƯỚNG --}}
            <div class="flex justify-between items-center bg-slate-100 p-6 rounded-2xl border border-slate-200">
                <a href="{{ route('questions.create') }}" class="text-slate-600 hover:text-slate-800 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại Bước 1
                </a>
                
                <button type="submit" 
                    class="px-10 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                    Hoàn tất & Lưu câu hỏi <i class="fa-solid fa-check-double"></i>
                </button>
            </div>
        </div>
    </form>
</div>

{{-- MODAL XEM TRƯỚC (PREVIEW) --}}
<div id="preview-modal" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col border border-slate-200 overflow-hidden">
        {{-- Header Modal --}}
        <div class="flex justify-between items-center bg-slate-50 p-4 border-b border-slate-200">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-microscope text-indigo-500"></i> Xem trước hiển thị thực tế
            </h3>
            <button type="button" onclick="closePreview()" class="text-slate-400 hover:text-rose-500 transition">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        
        {{-- Body Modal (Nơi chứa nội dung preview) --}}
        <div id="preview-body" class="p-8 overflow-y-auto text-slate-800 text-base leading-relaxed" style="min-height: 250px;">
            </div>
        
        {{-- Footer Modal --}}
        <div class="bg-slate-50 p-4 border-t border-slate-200 flex justify-end">
            <button type="button" onclick="closePreview()" class="px-6 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-300 transition">
                Đóng màn hình này
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        // 1. KHỞI TẠO TINYMCE
        // Lấy config content_style từ window (file app.js đã gắn)
        const tinyStyle = window.tinymceContentCss ? window.tinymceContentCss : '';

        if (window.tinymce) {
            window.tinymce.init({
                selector: '#editor-stem, #editor-explanation',
                license_key: 'gpl',
                skin: false,
                content_css: false,
                content_style: tinyStyle + '\n body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; }',
                height: 350,
                menubar: false,
                plugins: ['lists', 'link', 'code', 'table', 'image'],
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table image | code',
                
                // ==========================================
                // THÊM CẤU HÌNH UPLOAD ẢNH TỪ MÁY TÍNH Ở ĐÂY
                // ==========================================
                image_title: true, 
                automatic_uploads: true,
                file_picker_types: 'image',
                // ==========================================
                // THÊM CẤU HÌNH CĂN CHỈNH ẢNH Ở ĐÂY
                // ==========================================
                
                // 1. Mở tab "Advanced" (Nâng cao) để giáo viên có thể tự gõ style CSS nếu thích
                image_advtab: true, 

                // 2. Thêm menu thả xuống "Class" ngay trong hộp thoại ảnh
                image_class_list: [
                    { title: 'Mặc định (Inline - Chèn cùng dòng chữ)', value: '' },
                    { title: 'Căn trái & Bọc chữ (Float Left)', value: 'float-left mr-4 mb-2' },
                    { title: 'Căn phải & Bọc chữ (Float Right)', value: 'float-right ml-4 mb-2' },
                    { title: 'Nằm giữa 1 mình (Block Center)', value: 'block mx-auto my-4' },
                    { title: 'Hiển thị đầy đủ khung (Responsive)', value: 'max-w-full h-auto' }
                ],
                
                // ==========================================
                // Hàm này sẽ tạo ra nút "Duyệt qua..." (Browse) và mở cửa sổ chọn file
                file_picker_callback: function (cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    // Khi người dùng chọn file xong
                    input.onchange = function () {
                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = window.tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            // Gọi callback để nhét URL dạng blob vào ô Source của TinyMCE
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                        reader.readAsDataURL(file);
                    };

                    input.click(); // Tự động click để mở cửa sổ chọn file
                },
                // ==========================================

                setup: function (editor) {
                    editor.on('change keyup paste', function () {
                        editor.save();
                    });
                }
            });
        }

        // 2. HÀM MỞ PREVIEW MODAL VÀ RENDER TOÁN
        window.previewContent = function(editorId) {
            if (!window.tinymce) return alert('Trình soạn thảo TinyMCE chưa được tải!');
            
            const editor = window.tinymce.get(editorId);
            if (!editor) return alert('Không tìm thấy vùng soạn thảo tương ứng!');

            // Lấy nội dung HTML hiện tại
            const contentHTML = editor.getContent();
            const previewBody = document.getElementById('preview-body');
            const modal = document.getElementById('preview-modal');

            // Đổ HTML vào Modal
            previewBody.innerHTML = contentHTML || '<span class="text-slate-400 italic">Chưa có nội dung nào được nhập...</span>';

            // Kích hoạt render toán học KaTeX nếu hàm có sẵn từ app.js
            if (window.renderMathInElement) {
                window.renderMathInElement(previewBody, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false},
                        {left: '\\[', right: '\\]', display: true}
                    ],
                    throwOnError: false // Tránh crash toàn trang nếu gõ sai cú pháp
                });
            }

            // Mở modal (Đổi class hidden thành flex)
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        // 3. HÀM ĐÓNG MODAL
        window.closePreview = function() {
            const modal = document.getElementById('preview-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };
    });
</script>
@endpush
@endsection