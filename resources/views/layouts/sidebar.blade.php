<aside class="w-64 bg-white border-r border-slate-200 shadow-sm overflow-y-auto pt-20 relative z-10">
    <div class="p-4 mb-24">

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
                    <li><a href="{{ route('question-types.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-clipboard-question w-5 text-center mr-2 text-slate-400"></i> Loại câu
                            hỏi</a></li>
                    <li><a href="{{ route('cognitive-levels.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-brain w-5 text-center mr-2 text-slate-400"></i> Mức độ nhận thức</a></li>
                    <li><a href="{{ route('question-layouts.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-border-all w-5 text-center mr-2 text-slate-400"></i> Cấu hình hiển
                            thị</a></li>

                    {{-- THÊM MỚI: QUẢN LÝ QUYỀN --}}
                    <li><a href="{{ route('permissions.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-key w-5 text-center mr-2 text-slate-400"></i> Quản lý Quyền</a></li>
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
                    <li><a href="{{ route('assignments.index') }}"
                            class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                                class="fa-solid fa-shield-halved w-5 text-center mr-2 text-slate-400"></i> Phân công - cấp
                            quyền</a></li>
                    <li>
                        <a href="{{ route('topic-assignments.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ request()->routeIs('topic-assignments.*') ? 'bg-indigo-50 text-indigo-600 font-medium' : '' }}">
                            <i class="fa-solid fa-book-open-reader w-5 text-center"></i>
                            <span>Phân công chuyên đề</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endhasanyrole

        {{-- NHÓM CÂU HỎI: Chỉ những người có thẩm quyền mới thấy --}}
        @can('bien-soan-cau-hoi')
            <li>
                <a href="{{ route('questions.index') }}"
                    class="flex items-center p-2 text-slate-700 rounded-lg hover:bg-slate-100 group">
                    <i class="fa-solid fa-layer-group text-slate-400 group-hover:text-blue-600 transition duration-75"></i>
                    <span class="ms-3">Ngân hàng câu hỏi</span>
                </a>
            </li>
            {{-- Mục Upload câu hỏi: Yêu cầu ĐỒNG THỜI quyền biên soạn (từ group cha) và quyền upload --}}
            @can('upload-cau-hoi')
                <li><a href="{{ route('questions.upload') }}"
                        class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-md hover:bg-slate-100 hover:text-slate-900 transition"><i
                            class="fa-solid fa-cloud-arrow-up w-5 text-center mr-2 text-slate-400"></i> Upload câu hỏi</a>
                </li>
            @endcan
        @endcan

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

    {{-- PHẦN USER INFO & LOGOUT --}}
    <div class="fixed bottom-0 left-0 w-64 p-4 border-t border-slate-200 bg-white">
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
