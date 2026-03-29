document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('my-editor')) {
        window.tinymce.init({
            selector: '#my-editor',
            license_key: 'gpl',
            language: 'vi',
            // Chỉ định đường dẫn đến thư mục public
            base_url: '/js/tinymce', 
            suffix: '.min',
            height: 400,
            menubar: false,
            plugins: 'lists link code table',
            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | code',
            // Quan trọng: Để TinyMCE không "nuốt" mất mã TeX
            verify_html: false 
        });
    }
});

// 2. Gắn hàm previewContent vào object 'window' để HTML có thể gọi được qua onclick=""
window.previewContent = function() {
    let editor = window.tinymce.get('my-editor');
    if (!editor) return;

    let content = editor.getContent();
    let previewBox = document.getElementById('preview-box');
    
    if (previewBox) {
        previewBox.innerHTML = content;

        // Sử dụng window.renderMathInElement đã gán ở app.js
        if (typeof window.renderMathInElement === 'function') {
            window.renderMathInElement(previewBox, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false}, // Ngoặc đơn nội dòng
                    {left: '\\[', right: '\\]', display: true}   // Ngoặc vuông khối
                ],
                throwOnError: false
            });
        }
    }
};