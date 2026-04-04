@push('scripts')
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
@endpush