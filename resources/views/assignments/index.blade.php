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

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check text-green-500 mr-2"></i>
                <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('assignments.update') }}" method="POST">
            @csrf
            {{-- Lưu ý: Nếu ở routes bạn dùng Route::put/patch thì mở comment dòng dưới --}}
            {{-- @method('PUT') --}}
            
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
                                                   class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 cursor-pointer transition-all"
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

            <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-blue-500/30 transition-all flex items-center gap-2 font-medium">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu phân quyền
                </button>
            </div>
        </form>
    </div>
</div>
@endsection