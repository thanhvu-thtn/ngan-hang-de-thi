<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Ngân hàng câu hỏi')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased h-screen flex flex-col overflow-hidden">

    @include('layouts.navbar')

    <div class="flex flex-1 overflow-hidden">
        
        @include('layouts.sidebar')

        <main class="flex-1 bg-slate-50 p-8 overflow-y-auto">
            @yield('content')
        </main>
        
    </div>

    <footer class="bg-white border-t border-slate-200 py-3 shrink-0 relative z-20">
        <div class="text-center text-sm text-slate-500 font-medium">
            Copyright © by Nguyễn Thanh Vũ - THPT chuyên Hoàng Lê Kha Tây Ninh - 2026
        </div>
    </footer>

</body>
</html>