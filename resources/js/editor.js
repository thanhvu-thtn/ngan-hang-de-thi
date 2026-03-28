document.addEventListener('DOMContentLoaded', function() {
    // 1. Chỉ khởi tạo TinyMCE nếu trên trang thực sự có thẻ id="my-editor"
    if (document.getElementById('my-editor')) {
        tinymce.init({
            selector: '#my-editor',
            license_key: 'gpl', // Chìa khóa để chạy bản local không báo lỗi key
            language: 'vi',      // Gói ngôn ngữ tiếng Việt
            height: 400,
            menubar: false,
            plugins: 'lists link code table',
            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | code',
        });
    }
});

// 2. Gắn hàm previewContent vào object 'window' để HTML có thể gọi được qua onclick=""
window.previewContent = function() {
    // Kiểm tra xem editor có tồn tại không để tránh lỗi
    let editor = tinymce.get('my-editor');
    if (!editor) return;

    let content = editor.getContent();
    let previewBox = document.getElementById('preview-box');
    
    if (previewBox) {
        // Đổ nội dung HTML vào khung
        previewBox.innerHTML = content;

        // Gọi KaTeX để quét và dịch sang MathML
        if (typeof window.renderMathInElement === 'function') {
            window.renderMathInElement(previewBox, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                output: 'mathml', 
                throwOnError: false
            });
        }
    }
};