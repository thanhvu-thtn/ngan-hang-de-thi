@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Phân quyền Giáo viên</h2>
            <p class="text-sm text-gray-500 mt-1">Quản lý quyền hạn của các giáo viên trong bộ môn của bạn.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded shadow-sm mb-6" role="alert">
            <div class="flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ route('assignments.update') }}" method="POST">
        @csrf
        <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full w-full table-auto">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-xs leading-normal border-b border-gray-200">
                            <th class="py-4 px-6 text-left font-semibold">STT</th>
                            <th class="py-4 px-6 text-left font-semibold">Thông tin Giáo viên</th>
                            
                            @foreach($permissions as $key => $label)
                                <th class="py-4 px-6 text-center font-semibold">{{ $label }}</th>
                            @endforeach
                            
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        @forelse($teachers as $index => $teacher)
                            <tr class="border-b border-gray-100 hover:bg-slate-50 transition duration-150">
                                <td class="py-4 px-6 text-left whitespace-nowrap font-medium text-slate-700">
                                    {{ $index + 1 }}
                                    <input type="hidden" name="teacher_ids[]" value="{{ $teacher->id }}">
                                </td>
                                <td class="py-4 px-6 text-left">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                            {{ substr($teacher->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-700">{{ $teacher->name }}</span>
                                            <div class="text-xs text-gray-500 mt-0.5">{{ $teacher->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                
                                @foreach($permissions as $key => $label)
                                    <td class="py-4 px-6 text-center">
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" 
                                                   name="permissions[{{ $teacher->id }}][]" 
                                                   value="{{ $key }}"
                                                   class="w-5 h-5 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer transition duration-150"
                                                   {{ $teacher->hasPermissionTo($key) ? 'checked' : '' }}>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-gray-500">
                                    <i class="fa-regular fa-folder-open text-4xl mb-3 text-gray-300 block"></i>
                                    Không có giáo viên nào thuộc tổ bộ môn của bạn.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($teachers->count() > 0)
                <div class="bg-slate-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-6 rounded-lg shadow-sm transition duration-150 flex items-center">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu Thay Đổi
                    </button>
                </div>
            @endif
        </div>
    </form>
</div>
@endsection