<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Không được phép truy cập</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen text-center px-4">
   <div class="bg-white rounded-2xl shadow-xl border border-slate-100 px-12 pt-12 max-w-xl w-full flex flex-col items-center" style="padding-bottom: 3rem;">
        
        <div class="w-20 h-20 rounded-full bg-rose-100 flex items-center justify-center mb-8">
            <i class="fa-solid fa-shield-halved text-rose-500 text-4xl"></i>
        </div>

        <h1 class="text-7xl font-extrabold text-slate-800 mb-2">403</h1>
        
        <h2 class="text-2xl font-bold text-rose-600 mb-4">Bạn đã vào khu vực không được phép!</h2>
        
        <p class="text-slate-600 text-lg leading-relaxed mb-10 max-w-md">
            Tài khoản của bạn hiện tại không có quyền hạn để xem trang này. Vui lòng quay lại hoặc liên hệ với Quản trị viên nếu bạn cho rằng đây là một sự nhầm lẫn.
        </p>

        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-blue-600 text-white text-lg font-medium rounded-full shadow hover:bg-blue-700 transition duration-150" style="margin-bottom: 1rem;">
            <i class="fa-solid fa-arrow-left"></i>
            Quay về trang Dashboard
        </a>
        
    </div>
</body>
</html>