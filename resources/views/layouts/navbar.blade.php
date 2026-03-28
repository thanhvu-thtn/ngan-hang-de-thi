<header class="bg-slate-800 text-white shadow-md relative z-20 shrink-0">
    <div class="flex justify-between items-center px-6 h-16">
        
        <div class="flex items-center gap-2 cursor-pointer w-64">
            <div class="bg-slate-700 text-white p-1.5 rounded-lg shadow-sm border border-slate-600">
                <i class="fa-solid fa-layer-group text-xl"></i>
            </div>
            <span class="font-extrabold text-xl tracking-wider text-white">NHCH</span>
        </div>

        <nav class="hidden md:flex flex-1 justify-center space-x-2">
            <button data-target="menu-he-thong" class="nav-item px-4 py-2 rounded-md font-medium text-white bg-slate-900 shadow-inner transition hover:bg-slate-900">
                <i class="fa-solid fa-cogs me-1"></i> Hệ thống
            </button>
            <button data-target="menu-to-truong" class="nav-item px-4 py-2 rounded-md font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition">
                <i class="fa-solid fa-user-tie me-1"></i> Tổ trưởng
            </button>
            <button data-target="menu-cau-hoi" class="nav-item px-4 py-2 rounded-md font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition">
                <i class="fa-solid fa-database me-1"></i> Câu hỏi
            </button>
            <button data-target="menu-de-thi" class="nav-item px-4 py-2 rounded-md font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition">
                <i class="fa-solid fa-file-signature me-1"></i> Đề thi
            </button>
            <button data-target="menu-cham-thi" class="nav-item px-4 py-2 rounded-md font-medium text-slate-300 hover:bg-slate-700 hover:text-white transition">
                <i class="fa-solid fa-check-double me-1"></i> Chấm thi
            </button>
        </nav>

        <div class="flex items-center justify-end w-64">
            @auth
                <div class="flex items-center gap-3">
                    <span class="font-medium text-sm text-slate-200"><i class="fa-regular fa-user-circle me-1"></i> {{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded transition shadow-sm border border-red-600">
                            <i class="fa-solid fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex items-center gap-3">
                    <span class="font-medium text-sm text-slate-400">Khách</span>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-sm bg-slate-700 text-white border border-slate-600 hover:bg-slate-600 px-4 py-1.5 rounded transition shadow-sm">Đăng nhập</a>
                    @else
                        <a href="#" class="text-sm bg-slate-700 text-white border border-slate-600 hover:bg-slate-600 px-4 py-1.5 rounded transition shadow-sm">Đăng nhập</a>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</header>