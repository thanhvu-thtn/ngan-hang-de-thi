import './bootstrap';

// 1. Nhập KaTeX (Dùng CHUẨN file .mjs, tuyệt đối không gọi .min.js)
import 'katex/dist/katex.min.css';
import katex from 'katex/dist/katex.mjs';
import renderMathInElement from 'katex/dist/contrib/auto-render.mjs'; // Đổi luôn đuôi này thành .mjs

// Gán vào window để file editor.js có thể gọi được hàm này
window.katex = katex;
window.renderMathInElement = renderMathInElement;

// 2. Nhập TinyMCE (để soạn thảo nội dung)
import tinymce from 'tinymce';
import 'tinymce/themes/silver';
import 'tinymce/icons/default';
import 'tinymce/models/dom';

// Các plugin cần thiết cho trình soạn thảo
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';

// Gán vào window để editor.js nhận diện được đối tượng tinymce
window.tinymce = tinymce;

// 3. Tự động render KaTeX cho toàn trang khi tải xong
document.addEventListener("DOMContentLoaded", function() {
    const elements = document.querySelectorAll('.format-katex');
    if (elements.length > 0) {
        elements.forEach(el => {
            window.renderMathInElement(el, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},
                    {left: '\\[', right: '\\]', display: true}
                ],
                throwOnError: false
            });
        });
    }
});