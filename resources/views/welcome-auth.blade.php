@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white shadow-sm rounded-xl p-8 border border-slate-200">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-blue-500 text-white flex items-center justify-center text-2xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Xin chào, {{ $user->name }}!</h1>
                <p class="text-slate-500">Hôm nay là {{ date('d/m/Y') }}</p>
            </div>
        </div>

        <div class="space-y-4 text-lg text-slate-700 leading-relaxed">
            @if($role == 'Admin')
                <p>🚀 Bạn đã đăng nhập thành công với vai trò <span class="font-bold text-blue-600">Admin</span>.</p>
                <p>Bạn có <span class="underline decoration-blue-300">toàn quyền hành động</span> trên hệ thống này. Hãy cẩn trọng với các thao tác xóa dữ liệu!</p>
            
            @elseif($role == 'Tổ trưởng')
                <p>⭐ Bạn đã đăng nhập thành công với vai trò <span class="font-bold text-emerald-600">Tổ trưởng</span>.</p>
                <p>Bạn có toàn quyền quản lý chuyên đề và phân công giáo viên đối với <span class="font-bold text-slate-900">bộ môn của mình</span>.</p>

            @elseif($role == 'Giáo viên')
                <p>📝 Bạn đã đăng nhập thành công với vai trò <span class="font-bold text-amber-600">Giáo viên</span>.</p>
                
                @if($permissions->count() > 0)
                    <p>Hiện tại, bạn đã được phân công các quyền sau:</p>
                    <ul class="list-disc ml-8 space-y-1 text-slate-600">
                        @foreach($permissions as $p)
                            <li><span class="capitalize">{{ str_replace('-', ' ', $p) }}</span></li>
                        @endforeach
                    </ul>
                @else
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 my-4">
                        <p class="text-amber-700 font-medium italic">"Hiện tại bạn chưa được phân công nhiệm vụ cụ thể. Xin hẹn gặp khi khác hoặc liên hệ Tổ trưởng để được cấp quyền."</p>
                    </div>
                @endif
            @endif

            <hr class="my-6 border-slate-100">
            
            <p class="text-center text-slate-500 font-medium py-4 italic">
                ✨ Chúc bạn một ngày làm việc tốt lành! ✨
            </p>
        </div>
    </div>
</div>
@endsection