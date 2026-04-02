<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Ngân hàng câu hỏi')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased h-screen flex flex-col overflow-hidden">

    @include('layouts.navbar')

    <div class="flex flex-1 overflow-hidden">

        @include('layouts.sidebar')

        <main class="flex-1 bg-slate-50 p-8 overflow-y-auto relative">
            
            {{-- KHỐI HIỂN THỊ THÔNG BÁO GÓC TRÊN BÊN PHẢI (TOÀN CỤC) --}}
            @if (session('success'))
                <div class="auto-dismiss fixed top-24 right-8 z-50 p-4 min-w-[300px] text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 shadow-lg transition-opacity duration-500" role="alert">
                    <span class="font-bold"><i class="fa-solid fa-circle-check mr-1"></i> Thành công!</span> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="auto-dismiss fixed top-24 right-8 z-50 p-4 min-w-[300px] text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 shadow-lg transition-opacity duration-500" role="alert">
                    <span class="font-bold"><i class="fa-solid fa-circle-exclamation mr-1"></i> Lỗi!</span> {{ session('error') }}
                </div>
            @endif
            {{-- KẾT THÚC KHỐI THÔNG BÁO --}}

            <div class="mt-20">
                @yield('content')
            </div>
        </main>

    </div>

    <footer class="bg-white border-t border-slate-200 py-3 shrink-0 relative z-20">
        <div class="text-center text-sm text-slate-500 font-medium">
            Copyright © by Nguyễn Thanh Vũ - THPT chuyên Hoàng Lê Kha Tây Ninh - 2026
        </div>
    </footer>

    {{-- SCRIPT JS TỰ ĐỘNG TẮT THÔNG BÁO SAU 3 GIÂY --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.auto-dismiss');
            alerts.forEach(function (alert) {
                // Đợi 3 giây
                setTimeout(function () {
                    alert.style.opacity = '0'; // Mờ dần
                    // Đợi thêm 0.5s cho hiệu ứng mờ xong rồi xóa hẳn khỏi HTML
                    setTimeout(function () {
                        alert.remove();
                    }, 500); 
                }, 3000); 
            });
        });

    </script>
    @stack('scripts')
</body>

</html>