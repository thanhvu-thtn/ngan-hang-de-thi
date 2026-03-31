@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-shield-halved text-blue-500"></i>
            Phân công - Cấp quyền
        </h2>
        <p class="text-sm text-slate-500 mt-1">Quản lý và cấp quyền chuyên môn cho giáo viên trong tổ</p>
    </div>

    {{-- KHỐI CẢNH BÁO CHƯA LƯU --}}
    <div id="unsaved-warning" class="hidden mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 shadow-sm flex items-center justify-between transition-all duration-300">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-100 rounded-lg">
                <i class="fa-solid fa-triangle-exclamation text-xl text-amber-600 animate-pulse"></i>
            </div>
            <div>
                <p class="font-bold text-amber-800">Bạn có thay đổi chưa được lưu!</p>
                <p class="text-sm text-amber-700 mt-0.5">Dữ liệu phân quyền vừa bị thay đổi. Nhấn <span class="font-semibold text-blue-600">Lưu phân quyền</span> để cập nhật, hoặc nhấn <span class="font-semibold text-slate-600">Hủy thay đổi</span> để khôi phục.</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form id="permissions-form" action="{{ route('assignments.update') }}" method="POST">
            @csrf
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm font-semibold">
                            <th class="py-4 px-6 w-16 text-center">STT</th>
                            <th class="py-4 px-6">Giáo viên</th>
                            
                            @foreach($permissions as $permission)
                                <th class="py-4 px-6 text-center border-l border-slate-200">
                                    {{ $permission->name }}
                                </th>
                            @endforeach
                            
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($teachers as $index => $teacher)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 text-center text-slate-500 text-sm">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-medium text-slate-800">{{ $teacher->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $teacher->email }}</div>
                                    
                                    <input type="hidden" name="teacher_ids[]" value="{{ $teacher->id }}">
                                </td>
                                
                                @foreach($permissions as $permission)
                                    <td class="py-4 px-6 text-center border-l border-slate-100">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   name="permissions[{{ $teacher->id }}][]" 
                                                   value="{{ $permission->name }}"
                                                   class="permission-checkbox w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all"
                                                   {{ $teacher->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($permissions) + 2 }}" class="py-8 px-6 text-center text-slate-500">
                                    <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-300"></i>
                                    <p>Chưa có giáo viên nào trong tổ chuyên môn của bạn.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                {{-- NÚT HỦY THAY ĐỔI (Ẩn mặc định) --}}
                <button type="reset" id="reset-btn" class="hidden px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition-all duration-300 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-rotate-left"></i> Hủy thay đổi
                </button>

                {{-- NÚT LƯU --}}
                <button type="submit" id="save-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-500/30 transition-all duration-300 flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu phân quyền
                </button>
            </div>
        </form>
    </div>
</div>

{{-- SCRIPT XỬ LÝ SỰ KIỆN CHƯA LƯU THÔNG MINH --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('permissions-form');
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const warningBanner = document.getElementById('unsaved-warning');
        const saveBtn = document.getElementById('save-btn');
        const resetBtn = document.getElementById('reset-btn');

        // Bước 1: Lưu lại trạng thái ban đầu của tất cả checkbox khi vừa load trang
        checkboxes.forEach(function(cb) {
            cb.dataset.initialChecked = cb.checked;
        });

        // Hàm kiểm tra xem có sự thay đổi THỰC SỰ nào không
        function checkDirtyState() {
            let isDirty = false;
            
            // So sánh trạng thái hiện tại với trạng thái ban đầu
            checkboxes.forEach(function(cb) {
                // Phải chuyển cb.checked thành chuỗi để so sánh với dataset
                if (cb.checked.toString() !== cb.dataset.initialChecked) {
                    isDirty = true;
                }
            });

            if (isDirty) {
                // Bật cảnh báo và đổi màu nút
                warningBanner.classList.remove('hidden');
                resetBtn.classList.remove('hidden');
                saveBtn.classList.add('ring-4', 'ring-amber-300', 'bg-amber-600', 'hover:bg-amber-700');
                saveBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            } else {
                // Tắt cảnh báo, trả mọi thứ về bình thường
                warningBanner.classList.add('hidden');
                resetBtn.classList.add('hidden');
                saveBtn.classList.remove('ring-4', 'ring-amber-300', 'bg-amber-600', 'hover:bg-amber-700');
                saveBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
            
            return isDirty;
        }

        // Bước 2: Bắt sự kiện mỗi khi người dùng tick/bỏ tick
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', checkDirtyState);
        });

        // Bước 3: Bắt sự kiện khi nhấn nút "Hủy thay đổi" (type="reset")
        form.addEventListener('reset', function() {
            // setTimeout để chờ trình duyệt reset form xong mới chạy hàm check
            setTimeout(checkDirtyState, 10);
        });

        // Bước 4: Chặn rời trang nếu có thay đổi
        window.addEventListener('beforeunload', function (e) {
            if (checkDirtyState()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        // Bước 5: Bỏ chặn khi người dùng chủ động nhấn Lưu
        form.addEventListener('submit', function() {
            window.onbeforeunload = null;
        });
    });
</script>
@endsection