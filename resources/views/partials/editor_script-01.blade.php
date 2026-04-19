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

@push('scripts')
<script type="module">
    document.addEventListener("DOMContentLoaded", function() {
        // Biến toàn cục lưu ID của editor đang được mở
        let currentActiveEditorId = null;

        // 1. HÀM KHỞI TẠO TINYMCE CHO 1 VÙNG CỤ THỂ
        window.initEduBankEditor = function(selector) {
            const tinyStyle = window.tinymceContentCss ? window.tinymceContentCss : '';
            if (window.tinymce) {
                window.tinymce.init({
                    selector: selector,
                    license_key: 'gpl',
                    relative_urls: false,
                    remove_script_host: true,
                    convert_urls: false, 
                    skin: false,
                    content_css: false,
                    content_style: tinyStyle + '\n body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; font-size: 16px; }',
                    height: 350,
                    menubar: false,
                    plugins: ['lists', 'link', 'code', 'table', 'image'],
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table image | code',
                    
                    // ==========================================
                    // CẤU HÌNH UPLOAD ẢNH & CĂN CHỈNH (TỪ CODE CỦA BÁC)
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
                    
                    // Xử lý nút Browse để chọn file ảnh trực tiếp thành Base64/Blob
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
                    
                    // Sự kiện khi khởi tạo xong
                    setup: function(editor) {
                        editor.on('init', function() {
                            editor.focus(); // Tự động trỏ chuột vào để gõ ngay
                        });
                        editor.on('change keyup paste', function() {
                            editor.save(); // Đồng bộ dữ liệu xuống textarea ẩn
                        });
                    }
                });
            }
        };

        // 2. HÀM KÍCH HOẠT EDITOR (BẤM NÚT NÀO TẮT NÚT KIA)
        window.activateEditor = function(targetId) {
            // Nếu bấm lại đúng cái đang mở thì bỏ qua
            if (currentActiveEditorId === targetId) return;

            // Nếu đang có một Editor khác mở, hãy đóng nó lại
            if (currentActiveEditorId) {
                const oldEditor = window.tinymce.get(currentActiveEditorId);
                if (oldEditor) {
                    oldEditor.save(); // Đảm bảo lưu dữ liệu cuối cùng vào textarea
                    oldEditor.remove(); // Hủy giao diện TinyMCE
                }
            }

            // Mở Editor mới
            window.initEduBankEditor('#' + targetId);
            currentActiveEditorId = targetId; // Cập nhật trạng thái
        };

        // 3. HÀM XỬ LÝ PREVIEW MODAL THÔNG MINH
        window.previewContent = function(editorId) {
            let contentHTML = '';
            
            // Kiểm tra xem vùng này đang là TinyMCE hay Textarea thường
            const editor = window.tinymce.get(editorId);
            if (editor) {
                contentHTML = editor.getContent(); // Lấy từ TinyMCE
            } else {
                const textarea = document.getElementById(editorId);
                if (textarea) {
                    contentHTML = textarea.value; // Lấy từ Textarea thường nếu chưa bật TinyMCE
                } else {
                    return alert('Không tìm thấy vùng soạn thảo tương ứng!');
                }
            }

            // Gắn vào Modal
            const previewBody = document.getElementById('preview-body');
            const modal = document.getElementById('preview-modal');
            
            // Thay thế khoảng trắng xuống dòng thành thẻ <br> để dễ nhìn (nếu gõ từ textarea thường)
            if (!editor) {
                contentHTML = contentHTML.replace(/\n/g, '<br>');
            }

            previewBody.innerHTML = contentHTML || '<span class="text-slate-400 italic">Chưa có nội dung nào được nhập...</span>';

            // Dịch công thức Toán học bằng KaTeX
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

            // Hiển thị modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        // 4. ĐÓNG MODAL
        window.closePreview = function() {
            const modal = document.getElementById('preview-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };
    });
</script>
@endpush