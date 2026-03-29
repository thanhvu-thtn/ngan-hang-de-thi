@extends('layouts.main')

@section('title', 'Thêm Môn học mới')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('subjects.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Thêm Môn học mới</h2>
            <p class="text-slate-500 text-sm mt-1">Nhập thông tin cho môn học mới vào hệ thống</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            
            <div class="p-6">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Tên môn học <span class="text-rose-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" 
                        class="w-full px-4 py-2.5 border @error('name') border-rose-300 bg-rose-50 @else border-slate-300 bg-slate-50 focus:bg-white @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none" 
                        placeholder="Ví dụ: Toán học, Vật lí..." required autofocus>
                    
                    @error('name')
                        <p class="mt-2 text-sm text-rose-500"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end gap-3">
                <a href="{{ route('subjects.index') }}" class="px-5 py-2 text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 font-medium transition shadow-sm">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu môn học
                </button>
            </div>
        </form>
    </div>
</div>
@endsection