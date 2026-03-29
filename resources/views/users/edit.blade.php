@extends('layouts.main')

@section('title', 'Cập nhật Người dùng')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('users.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Cập nhật Người dùng</h2>
            <p class="text-slate-500 text-sm mt-1">Chỉnh sửa thông tin tài khoản: <span class="font-semibold text-blue-600">{{ $user->name }}</span></p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                        Họ và tên <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all @error('name') border-rose-500 @enderror">
                    @error('name') <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                        Địa chỉ Email <span class="text-rose-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all @error('email') border-rose-500 @enderror">
                    @error('email') <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                    Mật khẩu mới <span class="text-slate-400 font-normal text-xs ml-1">(Bỏ trống nếu không muốn đổi)</span>
                </label>
                <input type="password" name="password" id="password" minlength="8"
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all @error('password') border-rose-500 @enderror"
                    placeholder="Nhập ít nhất 8 ký tự...">
                @error('password') <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-2">
                        Vai trò (Quyền hạn) <span class="text-rose-500">*</span>
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                        <option value="">-- Chọn vai trò --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $userRole) == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="subject_id" class="block text-sm font-semibold text-slate-700 mb-2">
                        Thuộc Môn học (Dành cho Tổ trưởng / Giáo viên)
                    </label>
                    <select name="subject_id" id="subject_id"
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                        <option value="">-- Không phân môn (Admin) --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', $user->subject_id) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection