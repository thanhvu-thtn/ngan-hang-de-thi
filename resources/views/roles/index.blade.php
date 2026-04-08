@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-users-gear text-blue-500"></i>
                Quản lý Vai trò & Quyền hạn
            </h2>
            <p class="text-sm text-slate-500 mt-1">Thiết lập quyền hạn mặc định cho từng nhóm Vai trò trong hệ thống</p>
        </div>
    </div>

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Form Phân Quyền --}}
    <form action="{{ route('roles.update') }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-700 text-sm">
                            <th class="p-4 font-bold border-b border-slate-200 sticky left-0 bg-slate-100 z-10 w-1/4 shadow-[inset_-1px_0_0_#e2e8f0]">
                                Danh sách Quyền hạn
                            </th>
                            
                            {{-- Lặp in ra các cột Vai trò --}}
                            @foreach($roles as $role)
                                <th class="p-4 font-bold border-b border-slate-200 text-center min-w-[120px]">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs uppercase">
                                        {{ $role->name }}
                                    </span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-600 divide-y divide-slate-100">
                        
                        {{-- Lặp in ra các hàng Quyền hạn --}}
                        @foreach($permissions as $permission)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-medium text-slate-700 sticky left-0 bg-white group-hover:bg-slate-50 shadow-[inset_-1px_0_0_#e2e8f0]">
                                    {{ $permission->name }}
                                </td>
                                
                                {{-- Lặp in ra các Checkbox tương ứng với giao điểm Quyền - Vai trò --}}
                                @foreach($roles as $role)
                                    <td class="p-4 text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   name="permissions[{{ $role->id }}][]" 
                                                   value="{{ $permission->name }}"
                                                   class="w-5 h-5 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 cursor-pointer"
                                                   {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
            
            {{-- Footer / Submit Button --}}
            <div class="p-5 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 rounded-b-2xl">
                <button type="reset" class="px-5 py-2.5 text-slate-600 font-medium hover:bg-slate-200 rounded-xl transition">
                    Hủy thay đổi
                </button>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu cấu hình phân quyền
                </button>
            </div>
        </div>
    </form>
    
</div>
@endsection