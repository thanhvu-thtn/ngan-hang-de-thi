@extends('layouts.main')

@section('title', 'Quản lý Người dùng')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-users text-blue-600 mr-2"></i>Quản lý Người dùng</h2>
            <p class="text-slate-500 text-sm mt-1">Quản lý tài khoản và phân quyền hệ thống</p>
        </div>
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Thêm người dùng
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 p-4 rounded-lg mb-6 border border-emerald-200 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-xl"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 text-rose-600 p-4 rounded-lg mb-6 border border-rose-200 flex items-center gap-3">
            <i class="fa-solid fa-triangle-exclamation text-xl"></i>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-700 font-semibold border-b border-slate-200 uppercase text-xs">
                <tr>
                    <th class="px-4 py-4 w-16 text-center">STT</th>
                    <th class="px-4 py-4">Họ và tên</th>
                    <th class="px-4 py-4">Email</th>
                    <th class="px-4 py-4">Vai trò</th>
                    <th class="px-4 py-4">Môn học</th>
                    <th class="px-4 py-4 w-32 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-4 text-center text-slate-500 font-medium">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-4 py-4 font-bold text-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                {{ $user->name }}
                            </div>
                        </td>
                        <td class="px-4 py-4 text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-4">
                            @foreach($user->roles as $role)
                                @if($role->name == 'Admin')
                                    <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-rose-200">{{ $role->name }}</span>
                                @elseif($role->name == 'Tổ trưởng')
                                    <span class="bg-amber-100 text-amber-700 px-2.5 py-1 rounded-md text-xs font-semibold border border-amber-200">{{ $role->name }}</span>
                                @else
                                    <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-xs font-semibold border border-blue-200">{{ $role->name }}</span>
                                @endif
                            @endforeach
                        </td>
                        <td class="px-4 py-4">
                            @if($user->subject)
                                <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md text-xs font-medium">{{ $user->subject->name }}</span>
                            @else
                                <span class="text-slate-400 italic text-xs">Không quản lý môn</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-amber-500 hover:text-amber-600 bg-amber-50 hover:bg-amber-100 p-1.5 rounded transition" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" 
                                    onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 p-1.5 rounded transition" title="Xóa">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                            <i class="fa-solid fa-users-slash text-4xl mb-3 text-slate-300 block"></i>
                            Chưa có dữ liệu người dùng.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($users->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection