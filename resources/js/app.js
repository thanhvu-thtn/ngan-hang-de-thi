import './bootstrap';
// Khởi tạo Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// 1. Nhập KaTeX (Dùng CHUẨN file .mjs, tuyệt đối không gọi .min.js)
import 'katex/dist/katex.min.css';
import katex from 'katex/dist/katex.mjs';
import renderMathInElement from 'katex/dist/contrib/auto-render.mjs'; // Đổi luôn đuôi này thành .mjs

// Gán vào window để file editor.js có thể gọi được hàm này
window.katex = katex;
window.renderMathInElement = renderMathInElement;

// ===============================================
// 2. Nhập TinyMCE (Phiên bản cấu hình Offline cho Vite)
// ===============================================
import tinymce from 'tinymce/tinymce'; // Gọi file core
import 'tinymce/models/dom/model';
import 'tinymce/themes/silver';
import 'tinymce/icons/default';

// Các plugin cần thiết
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/code';
import 'tinymce/plugins/table';
import 'tinymce/plugins/image';

// BẮT BUỘC CHO OFFLINE: Import CSS giao diện (Vite sẽ gộp nó vào app.css)
import 'tinymce/skins/ui/oxide/skin.css';

// BẮT BUỘC CHO OFFLINE: Import CSS của nội dung bên trong iframe dưới dạng chuỗi (?inline)
import contentCss from 'tinymce/skins/content/default/content.css?inline';
import contentUiCss from 'tinymce/skins/ui/oxide/content.css?inline';

// Gán ra window
window.tinymce = tinymce;
// Ném chuỗi CSS nội dung ra window để cấu hình file Blade có thể lấy được
window.tinymceContentCss = contentCss + '\n' + contentUiCss;

// 3. Tự động render KaTeX cho toàn trang khi tải xong
document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll('.format-katex');
    if (elements.length > 0) {
        elements.forEach(el => {
            window.renderMathInElement(el, {
                delimiters: [
                    { left: '$$', right: '$$', display: true },
                    { left: '$', right: '$', display: false },
                    { left: '\\(', right: '\\)', display: false },
                    { left: '\\[', right: '\\]', display: true }
                ],
                macros: {
                    ",": function (context) {
                        if (!context || typeof context.future !== 'function') {
                            return "\\mathord{\\char`,}";
                        }
                        var nextToken = context.future();
                        if (nextToken && nextToken.text === " ") {
                            return "\\mathpunct{\\char`,}";
                        }
                        return "\\mathord{\\char`,}";
                    }
                },
                throwOnError: false
            });
        });
    }
});