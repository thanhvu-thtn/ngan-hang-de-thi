@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-2xl">
    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shadow-inner">
                <i class="fa-solid fa-pen-to-square text-lg"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-800">Sửa Cấu hình: {{ $questionLayout->name }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">Cập nhật thông tin bố cục chia cột</p>
            </div>
        </div>

        <form action="{{ route('question-layouts.update', $questionLayout->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tên hiển thị <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-heading"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name', $questionLayout->name) }}" 
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 outline-none placeholder:text-slate-400" 
                            placeholder="Nhập tên cấu hình (VD: Một hàng 4 cột)" required>
                    </div>
                    @error('name') 
                        <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> 
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Mã Code <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-barcode"></i>
                            </div>
                            <input type="text" name="code" value="{{ old('code', $questionLayout->code) }}" 
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 uppercase focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 outline-none font-mono tracking-wider" 
                                placeholder="VD: 1X4" required>
                        </div>
                        @error('code') 
                            <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tỉ lệ (Ratio) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="fa-solid fa-percent"></i>
                            </div>
                            <input type="number" name="ratio" value="{{ old('ratio', $questionLayout->ratio) }}" min="0.1" max="1.0" step="0.1" 
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200 outline-none font-mono" 
                                required>
                        </div>
                        @error('ratio') 
                            <p class="text-rose-500 text-sm mt-1.5 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> 
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('question-layouts.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-xmark"></i> Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm shadow-blue-500/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Cập nhật
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection