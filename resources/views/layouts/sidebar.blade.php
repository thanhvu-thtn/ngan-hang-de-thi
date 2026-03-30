<aside class="w-64 bg-white border-r border-slate-200 shadow-sm overflow-y-auto relative z-10">
    <div class="p-4">

        {{-- NHÓM HỆ THỐNG: Chỉ dành cho Admin --}}
        @hasrole('Admin')
            <div id="menu-he-thong" class="sidebar-group block">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Hệ thống</h3>
                <ul class="space-y-1">
                    <li><a href="{{ route('users.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-users w-5 text-center mr-2 text-slate-400"></i> Quản lý Users</a></li>
                    <li><a href="{{ route('subjects.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-book w-5 text-center mr-2 text-slate-400"></i> Quản lý Môn học</a></li>
                    <li><a href="{{ route('topic-types.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-tags w-5 text-center mr-2 text-slate-400"></i> Loại chuyên đề</a></li>
                    {{-- ĐÂY LÀ PHẦN MỚI THÊM --}}
                    <li><a href="{{ route('question-types.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-clipboard-question w-5 text-center mr-2 text-slate-400"></i> Loại câu
                            hỏi</a></li>
                </ul>
            </div>
        @endhasrole

        {{-- NHÓM TỔ TRƯỞNG: Admin và Tổ trưởng đều thấy --}}
        @hasanyrole('Admin|Tổ trưởng')
            <div id="menu-to-truong" class="sidebar-group mt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Tổ trưởng</h3>
                <ul class="space-y-1">
                    <li><a href="{{ route('topics.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-list-check w-5 text-center mr-2 text-slate-400"></i> Quản lí chuyên
                            đề</a></li>
                    {{-- Đây là mục quan quan trọng để phân quyền cho GV --}}
                    {{-- <li><a href="{{ route('assignment.index') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i class="fa-solid fa-shield-halved w-5 text-center mr-2 text-slate-400"></i> Phân công quyền</a></li> --}}
                    <li><a href="{{ route('assignments.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-shield-halved w-5 text-center mr-2 text-slate-400"></i> Phân công - cấp
                            quyền</a></li>
                </ul>
            </div>
        @endhasanyrole

        {{-- NHÓM CÂU HỎI: Hiện dựa trên quyền biên soạn/biên tập --}}
        @canany(['bien-soan-cau-hoi', 'bien-tap-cau-hoi'])
            <div id="menu-cau-hoi" class="sidebar-group mt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Câu hỏi</h3>
                <ul class="space-y-1">
                    @can('bien-soan-cau-hoi')
                        <li><a href="#"
                                class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                    class="fa-solid fa-plus-circle w-5 text-center mr-2 text-slate-400"></i> Thêm mới câu
                                hỏi</a></li>
                    @endcan
                    @can('bien-tap-cau-hoi')
                        <li><a href="#"
                                class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                    class="fa-solid fa-edit w-5 text-center mr-2 text-slate-400"></i> Biên tập câu hỏi</a></li>
                    @endcan
                </ul>
            </div>
        @endcanany

        {{-- NHÓM ĐỀ THI: Hiện nếu có quyền tạo đề --}}
        @can('tao-de-thi')
            <div id="menu-de-thi" class="sidebar-group mt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Đề thi</h3>
                <ul class="space-y-1">
                    <li><a href="#"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-table w-5 text-center mr-2 text-slate-400"></i> Xây dựng đề thi</a></li>
                </ul>
            </div>
        @endcan

        {{-- NHÓM CHẤM THI: Hiện nếu có quyền chấm bài --}}
        @can('cham-bai')
            <div id="menu-cham-thi" class="sidebar-group mt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Chấm thi</h3>
                <ul class="space-y-1">
                    <li><a href="#"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-check w-5 text-center mr-2 text-slate-400"></i> Chấm trắc nghiệm</a></li>
                </ul>
            </div>
        @endcan

    </div>

    {{-- PHẦN USER INFO & LOGOUT (Giữ nguyên cấu trúc của bạn) --}}
    <div class="absolute bottom-0 left-0 w-64 p-4 border-t border-slate-200 bg-white">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'Người dùng' }}</p>
                <p class="text-xs text-slate-500 truncate">
                    {{ auth()->user()->roles->pluck('name')->first() ?? 'Chưa cấp quyền' }}</p>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-4 py-2.5 text-rose-600 rounded-lg hover:bg-rose-50 transition font-medium">
                <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</aside>
