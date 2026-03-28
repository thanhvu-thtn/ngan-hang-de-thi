import './bootstrap';
import './menu';

// Import KaTeX và Auto-render từ thư mục node_modules
import katex from 'katex';
import renderMathInElement from 'katex/dist/contrib/auto-render.mjs';

// Import CSS của KaTeX
import 'katex/dist/katex.min.css';

// Gắn vào window để các file Blade (.blade.php) có thể gọi được trực tiếp
window.katex = katex;
window.renderMathInElement = renderMathInElement;

// Import cấu hình TinyMCE & Preview của chúng ta
import './editor';

// Tự động render toàn bộ trang khi load xong (áp dụng cho các trang đọc câu hỏi)
document.addEventListener("DOMContentLoaded", function() {
    renderMathInElement(document.body, {
        delimiters: [
            {left: '$$', right: '$$', display: true},
            {left: '$', right: '$', display: false},
            {left: '\\(', right: '\\)', display: false},
            {left: '\\[', right: '\\]', display: true}
        ],
        output: 'mathml', 
        throwOnError: false
    });
});