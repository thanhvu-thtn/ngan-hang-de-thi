@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-xl">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-blue-500"></i> Chỉnh sửa Quyền
        </h2>
    </div>

    @if($errors->any())
        <div class="bg-rose-50 border-l-4 border-rose-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <p class="text-rose-700 text-sm font-medium"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $errors->first() }}</p>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">Mã quyền hiện tại</label>
                <input type="text" value="{{ $permission->name }}" disabled class="w-full px-4 py-2 bg-slate-100 border border-slate-300 rounded-lg text-slate-500 cursor-not-allowed">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Tên quyền mới</label>
                <input type="text" name="name" value="{{ $permission->name }}" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                <p class="text-xs text-rose-500 mt-2 font-medium">⚠️ Cảnh báo: Việc đổi tên quyền có thể làm các phần code đang sử dụng @@can('tên-cũ') bị lỗi. Hãy cân nhắc kỹ trước khi đổi.</p>
            </div>

            <div class="flex gap-3 justify-end">
                <a href="{{ route('permissions.index') }}" class="px-4 py-2 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg font-medium transition">Hủy bỏ</a>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium transition shadow-sm shadow-blue-500/30">
                    Lưu cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
@endsection