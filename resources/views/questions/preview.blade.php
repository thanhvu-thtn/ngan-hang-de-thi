@extends('layouts.main')

@section('title', 'Kiểm tra dữ liệu tải lên')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800"><i class="fa-solid fa-clipboard-check text-blue-600 mr-2"></i>Kiểm tra dữ liệu</h2>
            <p class="text-slate-500 text-sm mt-1">Vui lòng kiểm tra lại nội dung các câu hỏi trước khi lưu vào hệ thống.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-sm">
                        <th class="py-3 px-4 font-medium w-16 text-center">STT</th>
                        <th class="py-3 px-4 font-medium w-24 text-center">Loại</th>
                        <th class="py-3 px-4 font-medium">Tên / Mục tiêu</th>
                        <th class="py-3 px-4 font-medium">Nội dung câu hỏi (Stem)</th>
                        <th class="py-3 px-4 font-medium w-32 text-center">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @foreach($questions as $index => $q)
                        @php
                            // Kiểm tra logic cơ bản: Thiếu Type hoặc Stem thì báo lỗi
                            $isValid = !empty($q['type']) && !empty($q['stem']);
                        @endphp
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-3 px-4 text-center font-medium text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 text-slate-700 rounded text-xs font-bold">{{ $q['type'] ?: 'N/A' }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-medium text-slate-800 mb-1">{{ $q['name'] ?: '(Không có tên)' }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $q['objective'] }}</p>
                            </td>
                            <td class="py-3 px-4">
                                {{-- In HTML dạng thô để xem toán/ảnh có hiển thị đúng không --}}
                                <div class="prose prose-sm max-w-none text-slate-700 format-katex line-clamp-3">
                                    {!! $q['stem'] !!}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($isValid)
                                    <span class="inline-flex items-center gap-1 text-emerald-600 bg-emerald-50 px-2 py-1 rounded text-xs font-medium">
                                        <i class="fa-solid fa-check-circle"></i> Hợp lệ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-rose-600 bg-rose-50 px-2 py-1 rounded text-xs font-medium" title="Thiếu Type hoặc Stem">
                                        <i class="fa-solid fa-circle-exclamation"></i> Lỗi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Form Xác nhận lưu --}}
    <form action="{{ route('questions.upload.import') }}" method="POST" class="flex justify-end gap-3">
        @csrf
        <input type="hidden" name="batch_id" value="{{ $batchId }}">
        
        <a href="{{ route('questions.upload') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition font-medium">Tải lại file khác</a>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i> Xác nhận Lưu vào DB
        </button>
    </form>
</div>
@endsection