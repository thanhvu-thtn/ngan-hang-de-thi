document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Cấu hình và Khởi tạo TinyMCE
    if (window.tinymce) {
        window.tinymce.init({
            selector: '.tinymce-instance', // Áp dụng cho class này (cả 2 textarea)
            height: 350,
            menubar: 'file edit view insert format tools table',
            plugins: [
                'lists', 'link', 'code', 'table', 'image'
            ],
            toolbar: 'undo redo | blocks | bold italic underline | ' +
                     'alignleft aligncenter alignright alignjustify | ' +
                     'bullist numlist outdent indent | table image | code',
            
            // Do bạn load qua Vite, đôi khi TinyMCE sẽ báo lỗi thiếu CSS giao diện (skin)
            // Nếu bị lỗi giao diện, bạn giữ 2 dòng dưới đây để nó không tìm file css mặc định nữa
            skin: false,
            content_css: false,
            
            branding: false, // Tắt chữ "Powered by TinyMCE" cho chuyên nghiệp
            promotion: false, 
            
            setup: function (editor) {
                // Có thể bắt sự kiện gõ phím ở đây nếu sau này muốn làm auto-save
                editor.on('change', function () {
                    editor.save(); // Đồng bộ nội dung về textarea gốc ẩn bên dưới
                });
            }
        });
    }

    // 2. Xử lý nút XEM TRƯỚC (Preview)
    document.querySelectorAll('.btn-preview-math').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target'); // Lấy id (ví dụ: editor-question)
            
            // Lấy các DOM elements
            const containerEditor = document.getElementById(`container-${targetId}`);
            const containerPreview = document.getElementById(`preview-${targetId}`);
            const contentDiv = containerPreview.querySelector('.preview-content');

            // Lấy nội dung HTML từ instance TinyMCE
            if (window.tinymce.get(targetId)) {
                const rawContent = window.tinymce.get(targetId).getContent();
                contentDiv.innerHTML = rawContent;
            }

            // Gọi hàm render KaTeX từ app.js (truyền vào đúng cái thẻ div nội dung)
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

            // Thay đổi giao diện: Ẩn Editor, Hiện Preview, Ẩn nút "Xem trước"
            containerEditor.classList.add('hidden');
            containerPreview.classList.remove('hidden');
            this.classList.add('hidden');
        });
    });

    // 3. Xử lý nút ĐÓNG (Quay lại soạn thảo)
    document.querySelectorAll('.btn-close-preview').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            
            // Lấy các DOM elements
            const containerEditor = document.getElementById(`container-${targetId}`);
            const containerPreview = document.getElementById(`preview-${targetId}`);
            const btnPreview = document.querySelector(`.btn-preview-math[data-target="${targetId}"]`);

            // Thay đổi giao diện: Ẩn Preview, Hiện Editor, Hiện lại nút "Xem trước"
            containerPreview.classList.add('hidden');
            containerEditor.classList.remove('hidden');
            btnPreview.classList.remove('hidden');
        });
    });
});