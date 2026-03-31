<header class="bg-slate-800 text-white shadow-md relative z-20 shrink-0">
    <nav class="fixed top-0 z-50 w-full bg-[#1e293b] border-b border-slate-700 shadow-sm">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                {{-- Bên trái: Logo và Tên chương trình (Giữ nguyên) --}}
                <div class="flex items-center justify-start rtl:justify-end">
                    <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                            </path>
                        </svg>
                    </button>
                    <a href="/" class="flex ms-2 md:me-24">
                        <div class="p-1.5 bg-blue-500 rounded-lg me-3 shadow-lg shadow-blue-500/20">
                            <i class="fa-solid fa-layer-group text-white text-lg"></i>
                        </div>
                        <span class="self-center text-xl font-bold sm:text-2xl whitespace-nowrap text-white tracking-tight">NHCH</span>
                    </a>
                </div>

                {{-- PHẦN GIỮA: HIỂN THỊ VAI TRÒ VÀ MÔN HỌC --}}
                <div class="hidden md:flex items-center justify-center flex-1 gap-4">
                    {{-- Thẻ Vai trò --}}
                    <div class="flex items-center gap-2 px-3 py-1 bg-slate-800/50 border border-slate-700 rounded-full shadow-inner">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span class="text-[11px] font-bold text-slate-300 uppercase tracking-[0.1em]">
                            @if (Auth::check() && Auth::user()->hasRole('Admin'))
                                Quản trị viên
                            @elseif(Auth::check() && Auth::user()->hasRole('Tổ trưởng'))
                                Tổ trưởng
                            @else
                                Giáo viên
                            @endif
                        </span>
                    </div>

                    {{-- Dấu phân cách nhỏ --}}
                    <div class="h-4 w-[1px] bg-slate-600"></div>

                    {{-- Thẻ Phạm vi / Môn học --}}
                    <div class="flex items-center gap-2 text-slate-300">
                        <i class="fa-solid fa-shield-halved text-xs text-blue-400"></i>
                        <span class="text-sm font-medium">
                            @if (Auth::check() && Auth::user()->hasRole('admin'))
                                Toàn hệ thống
                            @else
                                Môn {{ Auth::user()->subject->name ?? 'Vật lý' }}
                            @endif
                        </span>
                    </div>
                </div>

                {{-- BÊN PHẢI: THÔNG TIN USER VÀ NÚT ĐĂNG XUẤT ĐỎ (TRỰC QUAN) --}}
                <div class="flex items-center gap-4">
                    {{-- Tên & Icon User --}}
                    <div class="hidden sm:flex items-center gap-2 text-slate-300">
                        <i class="fa-regular fa-circle-user text-xl"></i>
                        <div class="flex flex-col text-right">
                            <span class="text-sm font-medium text-white leading-tight">{{ Auth::user()->name ?? 'User' }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wider">{{ Auth::user()->roles->first()->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    {{-- Nút Đăng Xuất Đỏ --}}
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" 
                            class="flex items-center justify-center px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition-colors shadow-sm shadow-rose-600/30"
                            title="Đăng xuất">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>