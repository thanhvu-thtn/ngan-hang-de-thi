@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-6xl">
    
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-border-all text-blue-500"></i>
                Quản lý Cấu hình hiển thị
            </h2>
            <p class="text-sm text-slate-500 mt-1">Danh sách các bố cục chia cột cho câu hỏi và đáp án</p>
        </div>
        <a href="{{ route('question-layouts.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg shadow-sm shadow-blue-500/30 transition-all flex items-center gap-2 text-sm font-medium whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Thêm mới
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 flex items-center gap-3 p-4 text-sm text-emerald-800 border border-emerald-200 rounded-xl bg-emerald-50 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="py-4 px-6 font-semibold w-1/3">Tên hiển thị</th>
                        <th class="py-4 px-6 font-semibold w-1/4">Mã (Code)</th>
                        <th class="py-4 px-6 font-semibold text-center w-1/4">Tỉ lệ (Ratio)</th>
                        <th class="py-4 px-6 font-semibold text-right">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($layouts as $layout)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-4 px-6">
                            <span class="font-medium text-slate-800">{{ $layout->name }}</span>
                        </td>
                        
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-slate-100 text-slate-600 text-xs font-mono font-medium border border-slate-200 uppercase tracking-widest">
                                {{ $layout->code }}
                            </span>
                        </td>
                        
                        <td class="py-4 px-6 text-center text-slate-600 font-medium">
                            {{ $layout->ratio }}
                        </td>
                        
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('question-layouts.edit', $layout->id) }}" 
                                   class="w-8 h-8 flex items-center justify-center text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors" 
                                   title="Chỉnh sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                
                                <form action="{{ route('question-layouts.destroy', $layout->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa cấu hình này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 flex items-center justify-center text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors" 
                                            title="Xóa">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fa-solid fa-folder-open text-2xl text-slate-300"></i>
                                </div>
                                <p class="font-medium text-slate-600">Chưa có cấu hình hiển thị nào</p>
                                <p class="text-sm mt-1">Hãy bấm "Thêm mới" để tạo cấu hình đầu tiên.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($layouts->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $layouts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection