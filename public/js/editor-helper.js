window.MathJax = {
    tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']],
        displayMath: [['$$', '$$'], ['\\[', '\\]']],
        processEscapes: true
    },
    options: { enableMenu: false }
};

function updatePreview() {
    const editor = tinymce.get('editor-vattu');
    if (!editor) return;
    const content = editor.getContent();
    const previewContainer = document.getElementById('preview-content');
    if (previewContainer) {
        previewContainer.innerHTML = content;
        if (window.MathJax && window.MathJax.typesetPromise) {
            window.MathJax.typesetPromise([previewContainer]).catch(err => console.log(err));
        }
    }
}