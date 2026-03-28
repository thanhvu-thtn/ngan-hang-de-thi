<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Tailwind - Ngân hàng đề thi</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="{{ asset('js/mathjax/tex-chtml.js') }}" async></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('js/editor-helper.js') }}"></script>
    <script src="{{ asset('js/tinymce-config.js') }}"></script>
    @include('partials.tinymce-mathjax.tinymce-setup')
    
</head>
<body class="bg-slate-100 min-h-screen p-4 md:p-10">

    <div class="max-w-5xl mx-auto">
        <header class="bg-blue-600 text-white p-6 rounded-t-xl shadow-lg">
            <h1 class="text-3xl font-extrabold tracking-tight">Hệ thống Ngân hàng đề thi</h1>
            <p class="opacity-80 mt-2">Đang kiểm tra: Tailwind CSS + MathJax + TinyMCE</p>
        </header>

        <div class="bg-white p-6 shadow-md border-x">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-700 flex items-center">
                        <span class="w-2 h-6 bg-blue-500 rounded mr-2"></span>
                        Khu vực soạn thảo
                    </h2>
                    <textarea id="editor-vattu">Nhập công thức Vật Lí: $E = mc^2$</textarea>
                </div>

                <div class="space-y-4">
                    <h2 class="text-lg font-bold text-slate-700 flex items-center">
                        <span class="w-2 h-6 bg-green-500 rounded mr-2"></span>
                        Xem trước hiển thị
                    </h2>
                    <div id="preview-content" class="prose max-w-none p-4 border-2 border-dashed border-slate-200 rounded-lg min-h-[150px]">
                        </div>

                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-500 mb-2 italic text-center">Mô phỏng 4 đáp án (Grid 2x2):</p>
                        <div class="grid grid-cols-2 gap-2">
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition">A. $10\text{ m/s}$</div>
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition">B. $20\text{ m/s}$</div>
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition">C. $30\text{ m/s}$</div>
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100 transition">D. $40\text{ m/s}$</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <footer class="bg-slate-800 text-slate-400 p-4 rounded-b-xl text-center text-sm">
            Trạng thái hệ thống: <span class="text-green-400 font-mono">Ready</span> | Mac M1 Server Localhost
        </footer>
    </div>

</body>
</html>