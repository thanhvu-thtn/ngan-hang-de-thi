@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-key text-amber-500"></i> Quản lý Quyền (Permissions)
        </h2>
        <p class="text-sm text-slate-500 mt-1">Thêm, sửa, xóa các quyền hạn trong hệ thống</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <p class="text-green-700 text-sm font-medium"><i class="fa-solid fa-check-circle mr-1"></i> {{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <p class="text-rose-700 text-sm font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $errors->first() }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 sticky top-4">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">Thêm quyền mới</h3>
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Tên quyền (Tiếng Việt)</label>
                        <input type="text" name="name" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="VD: Quản lý điểm số" required>
                        <p class="text-xs text-slate-500 mt-2 italic">*Hệ thống sẽ tự động chuyển đổi thành dạng không dấu (vd: quan-ly-diem-so) để tối ưu bảo mật.</p>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fa-solid fa-plus mr-1"></i> Tạo quyền
                    </button>
                </form>
            </div>
        </div>

        <div class="md:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm">
                            <th class="py-3 px-5 font-semibold w-16 text-center">ID</th>
                            <th class="py-3 px-5 font-semibold">Mã Quyền (System Name)</th>
                            <th class="py-3 px-5 font-semibold w-24 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($permissions as $permission)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="py-3 px-5 text-center text-slate-500 font-mono text-sm">{{ $permission->id }}</td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 text-sm font-mono border border-slate-200">
                                        {{ $permission->name }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('permissions.edit', $permission->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-1.5 rounded transition">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quyền này không? Mọi người dùng đang có quyền này sẽ bị mất quyền.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 p-1.5 rounded transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection