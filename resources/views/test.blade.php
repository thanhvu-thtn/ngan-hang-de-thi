<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test TinyMCE & KaTeX (Local)</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
</head>
<body class="bg-slate-50 p-8 font-sans text-slate-800">

    <div class="max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-slate-800"><i class="fa-solid fa-flask text-blue-600 mr-2"></i>Test Biên soạn (Local Vite)</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
                <textarea id="my-editor" class="w-full">
                    <p>Giải phương trình: $x^2 - 4x + 4 = 0$</p>
                </textarea>
                <div class="mt-4 text-right">
                    <button onclick="previewContent()" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-md transition">
                        Dịch sang MathML & Xem trước
                    </button>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 flex flex-col">
                <div id="preview-box" class="flex-1 border-2 border-dashed border-slate-300 rounded-md p-4 bg-slate-50 overflow-y-auto"></div>
            </div>
        </div>
    </div>

    <script>
        tinymce.init({
            selector: '#my-editor',
            license_key: 'gpl', // Chìa khóa để chạy bản local không báo lỗi key
            language: 'vi',      // Nếu bạn đã tải gói ngôn ngữ tiếng Việt
            height: 400,
            menubar: false,
            plugins: 'lists link code table',
            toolbar: 'undo redo | formatselect | bold italic | bullist numlist | code',
        });

        function previewContent() {
            let content = tinymce.get('my-editor').getContent();
            let previewBox = document.getElementById('preview-box');
            previewBox.innerHTML = content;

            // Hàm này giờ được gọi từ window.renderMathInElement đã khai báo ở app.js
            window.renderMathInElement(previewBox, {
                delimiters: [
                    {left: '$$', right: '$$', display: true},
                    {left: '$', right: '$', display: false},
                    {left: '\\(', right: '\\)', display: false},  // Trong dòng (thêm lại)
                    {left: '\\[', right: '\\]', display: true}    // Khối (thêm lại)
                ],
                output: 'mathml', 
                throwOnError: false
            });
        }
    </script>
</body>
</html>