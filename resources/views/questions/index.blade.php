@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6">
    {{-- Header: Tiêu đề và Nút hành động --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-blue-500 rounded-lg shadow-lg shadow-blue-200">
                    <i class="fa-solid fa-layer-group text-white text-sm"></i>
                </div>
                Ngân hàng câu hỏi
            </h2>
            <p class="text-slate-500 text-sm mt-1">Quản lý và tìm kiếm câu hỏi theo hệ thống định danh tag.</p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Nút Tìm câu hỏi (Sau này nhấn vào sẽ bung Treeview) --}}
            <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-magnifying-glass text-blue-500"></i>
                Tìm câu hỏi
            </button>

            {{-- Nút Thêm mới --}}
            <a href="#" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                <i class="fa-solid fa-plus"></i>
                Thêm câu hỏi
            </a>
        </div>
    </div>

    {{-- Bảng danh sách câu hỏi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-200">
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-16 text-center">STT</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Mã định danh</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mô tả câu hỏi</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Mức độ</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Độ khó</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider w-36 text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                {{-- Dữ liệu mẫu 1 --}}
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4 text-slate-500 text-center font-medium">01</td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded border border-blue-100">
                            VL#10#CB#MD#CH01#
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-700 font-medium line-clamp-1">Xác định vận tốc tức thời của vật chuyển động thẳng biến đổi đều...</div>
                        <div class="text-xs text-slate-400 mt-0.5">Cập nhật: 12 phút trước</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold border border-emerald-100">
                            Thông hiểu
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">Trung bình</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <button title="Xem" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button title="Sửa" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button title="Xóa" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                {{-- Dữ liệu mẫu 2 --}}
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4 text-slate-500 text-center font-medium">02</td>
                    <td class="px-6 py-4">
                        <span class="font-mono text-blue-600 font-bold bg-blue-50 px-2 py-1 rounded border border-blue-100">
                            VL#10#CB#VD#CH05#
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-700">
                        <div class="font-medium line-clamp-1">Bài toán về định luật bảo toàn động lượng trong va chạm mềm...</div>
                        <div class="text-xs text-slate-400 mt-0.5">Cập nhật: 1 giờ trước</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-orange-50 text-orange-600 rounded-full text-xs font-bold border border-orange-100">
                            Vận dụng
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">Khó</td>
                    <td class="px-6 py-4 text-center">
                         <div class="flex items-center justify-center gap-2">
                            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition"><i class="fa-solid fa-eye"></i></button>
                            <button class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"><i class="fa-solid fa-trash-can"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Footer của bảng (Phân trang giả) --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
            <span class="text-xs text-slate-500 italic">* Lưu ý: Sử dụng bộ lọc "Tìm câu hỏi" để tối ưu tốc độ truy xuất.</span>
            <div class="text-sm text-slate-600">
                Hiển thị <b>2</b> trên <b>2.540</b> câu hỏi
            </div>
        </div>
    </div>
</div>
@endsection