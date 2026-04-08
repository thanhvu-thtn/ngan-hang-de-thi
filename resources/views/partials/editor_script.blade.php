{{-- @push('scripts')
<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        // 1. KHỞI TẠO TINYMCE
        // Lấy config content_style từ window (file app.js đã gắn)
        const tinyStyle = window.tinymceContentCss ? window.tinymceContentCss : '';
        const targetSelector = "{!! $selector ?? '#editor-stem, #editor-explanation' !!}";
        if (window.tinymce) {
            window.tinymce.init({
                selector: targetSelector,
                license_key: 'gpl',
                // === THÊM 3 DÒNG NÀY VÀO ĐÂY ===
                relative_urls: false,
                remove_script_host: true,
                convert_urls: false, // Dòng này khóa vĩnh viễn việc TinyMCE tự động đổi URL
                //---------------------
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
@endpush --}}


{{-- ==========================================
     1. GIAO DIỆN MODAL XEM TRƯỚC TOÁN HỌC
     ========================================== --}}
<div id="preview-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-fade-in-up">
        
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50">
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-eye text-blue-500"></i> Xem trước hiển thị Toán học
            </h3>
            <button type="button" onclick="window.closePreview()" class="text-slate-400 hover:text-red-500 transition w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div id="preview-body" class="p-8 overflow-y-auto custom-scrollbar text-slate-800 leading-relaxed text-lg math-content format-katex">
            {{-- Nội dung sẽ được đổ vào đây --}}
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex justify-end">
            <button type="button" onclick="window.closePreview()" class="px-6 py-2 bg-slate-200 text-slate-700 rounded-xl hover:bg-slate-300 font-medium transition shadow-sm">
                Đóng lại
            </button>
        </div>
    </div>
</div>

{{-- ==========================================
     2. SCRIPT KHỞI TẠO TINYMCE & XỬ LÝ ẢNH
     ========================================== --}}
@push('scripts')
<script type="module">
    // 1. HÀM KHỞI TẠO TINYMCE DÙNG CHUNG (Hỗ trợ cả AJAX)
    window.initEduBankEditor = function(targetSelector) {
        const tinyStyle = window.tinymceContentCss ? window.tinymceContentCss : '';
        
        if (window.tinymce) {
            // Xóa instance cũ nếu form load lại qua AJAX
            window.tinymce.remove(targetSelector); 
            
            window.tinymce.init({
                selector: targetSelector,
                license_key: 'gpl',
                relative_urls: false,
                remove_script_host: true,
                convert_urls: false, // Khóa vĩnh viễn việc TinyMCE tự động đổi URL
                skin: false,
                content_css: false,
                content_style: tinyStyle + '\n body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; }',
                height: 350,
                menubar: false,
                plugins: ['lists', 'link', 'code', 'table', 'image'],
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table image | code',
                
                // ==========================================
                // CẤU HÌNH UPLOAD ẢNH & CĂN CHỈNH
                // ==========================================
                image_title: true, 
                automatic_uploads: true,
                file_picker_types: 'image',
                image_advtab: true, 

                image_class_list: [
                    { title: 'Mặc định (Inline - Chèn cùng dòng chữ)', value: '' },
                    { title: 'Căn trái & Bọc chữ (Float Left)', value: 'float-left mr-4 mb-2' },
                    { title: 'Căn phải & Bọc chữ (Float Right)', value: 'float-right ml-4 mb-2' },
                    { title: 'Nằm giữa 1 mình (Block Center)', value: 'block mx-auto my-4' },
                    { title: 'Hiển thị đầy đủ khung (Responsive)', value: 'max-w-full h-auto' }
                ],
                
                // Xử lý nút Browse để chọn file ảnh trực tiếp
                file_picker_callback: function (cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');

                    input.onchange = function () {
                        var file = this.files[0];
                        var reader = new FileReader();
                        
                        reader.onload = function () {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = window.tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);

                            // Nhét URL dạng blob vào ô Source
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                        reader.readAsDataURL(file);
                    };

                    input.click(); 
                },

                // Đồng bộ nội dung vào textarea thực khi gõ
                setup: function (editor) {
                    editor.on('change keyup paste', function () {
                        editor.save();
                    });
                }
            });
        }
    };

    // 2. TỰ ĐỘNG CHẠY KHI LOAD TRANG (Cho các màn hình không dùng AJAX)
    document.addEventListener("DOMContentLoaded", function() {
        const targetSelector = "{!! $selector ?? '#editor-stem, #editor-explanation' !!}";
        window.initEduBankEditor(targetSelector);

        // 3. HÀM XỬ LÝ PREVIEW MODAL
        window.previewContent = function(editorId) {
            const editor = tinymce.get(editorId);
            if (!editor) return alert('Không tìm thấy vùng soạn thảo tương ứng!');

            const contentHTML = editor.getContent();
            const previewBody = document.getElementById('preview-body');
            const modal = document.getElementById('preview-modal');

            previewBody.innerHTML = contentHTML || '<span class="text-slate-400 italic">Chưa có nội dung nào được nhập...</span>';

            if (window.renderMathInElement) {
                window.renderMathInElement(previewBody, {
                    delimiters: [
                        {left: '$$', right: '$$', display: true},
                        {left: '$', right: '$', display: false},
                        {left: '\\(', right: '\\)', display: false},
                        {left: '\\[', right: '\\]', display: true}
                    ],
                    throwOnError: false
                });
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closePreview = function() {
            const modal = document.getElementById('preview-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };
    });
</script>
@endpush