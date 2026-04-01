@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 max-w-7xl">
    
    {{-- HEADER --}}
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-book-open-reader text-indigo-500"></i>
            Phân công Chuyên đề
        </h2>
        <p class="text-sm text-slate-500 mt-1">Giao nhiệm vụ biên soạn chuyên đề cho các giáo viên có thẩm quyền trong tổ.</p>
    </div>

    {{-- KHỐI CẢNH BÁO CHƯA LƯU --}}
    <div id="unsaved-warning" class="hidden mb-4 p-4 rounded-xl bg-amber-50 border border-amber-200 shadow-sm flex items-center justify-between transition-all duration-300">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-amber-100 rounded-lg">
                <i class="fa-solid fa-triangle-exclamation text-xl text-amber-600 animate-pulse"></i>
            </div>
            <div>
                <p class="font-bold text-amber-800">Bạn có thay đổi chưa được lưu!</p>
                <p class="text-sm text-amber-700 mt-0.5">Dữ liệu phân công vừa bị thay đổi. Nhấn <span class="font-semibold text-indigo-600">Lưu phân công</span> để cập nhật, hoặc nhấn <span class="font-semibold text-slate-600">Hủy thay đổi</span> để khôi phục.</p>
            </div>
        </div>
    </div>

    {{-- FORM PHÂN CÔNG --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <form id="assignment-form" action="{{ route('topic-assignments.update') }}" method="POST">
            @csrf
            
            <div class="divide-y divide-slate-100">
                @forelse($teachers as $teacher)
                    <div class="p-6 hover:bg-slate-50/50 transition-colors flex flex-col md:flex-row gap-6">
                        
                        {{-- THÔNG TIN GIÁO VIÊN VÀ BỘ LỌC (Bên trái) --}}
                        <div class="md:w-1/4 shrink-0 flex flex-col justify-start">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg shrink-0">
                                    {{ substr($teacher->name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800 text-base">{{ $teacher->name }}</h3>
                                    <p class="text-xs text-slate-500 mb-2">{{ $teacher->email }}</p>
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                        Được phép biên soạn
                                    </span>
                                </div>
                            </div>
                            
                            {{-- BỘ LỌC CHUYÊN ĐỀ DÀNH RIÊNG CHO TỪNG GIÁO VIÊN --}}
                            <div class="mt-6 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                <label class="block text-xs font-medium text-slate-600 mb-1.5 flex items-center gap-1.5">
                                    <i class="fa-solid fa-filter text-slate-400"></i> Lọc chuyên đề:
                                </label>
                                <select class="topic-filter w-full text-sm border-slate-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500 py-1.5" data-teacher-id="{{ $teacher->id }}">
                                    <option value="all">Tất cả loại chuyên đề</option>
                                    @if(isset($topicTypes))
                                        @foreach($topicTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <input type="hidden" name="teacher_ids[]" value="{{ $teacher->id }}">
                        </div>

                        {{-- DANH SÁCH CHUYÊN ĐỀ THEO KHỐI (Bên phải) --}}
                        <div class="md:w-3/4 grid grid-cols-1 lg:grid-cols-3 gap-6">
                            @foreach($topicsByGrade as $grade => $topics)
                                <div class="bg-white border border-slate-100 rounded-xl p-4 shadow-sm">
                                    <h4 class="text-sm font-bold text-slate-700 border-b border-slate-100 pb-2 mb-3 flex items-center gap-2">
                                        <i class="fa-solid fa-layer-group text-slate-400 text-xs"></i> Khối {{ $grade }}
                                    </h4>
                                    
                                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                                        @foreach($topics as $topic)
                                            <label class="topic-item teacher-{{ $teacher->id }} flex items-start gap-2.5 p-2 rounded-lg hover:bg-indigo-50/50 cursor-pointer border border-transparent hover:border-indigo-100 transition-all group"
                                                   data-type-id="{{ $topic->topic_type_id }}">
                                                <input type="checkbox" 
                                                       name="topics[{{ $teacher->id }}][]" 
                                                       value="{{ $topic->id }}"
                                                       class="topic-checkbox mt-0.5 w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2 cursor-pointer transition-all"
                                                       {{ $teacher->topics->contains('id', $topic->id) ? 'checked' : '' }}>
                                                <span class="text-sm text-slate-600 group-hover:text-indigo-700 leading-snug">{{ $topic->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @empty
                    <div class="py-12 px-6 text-center text-slate-500">
                        <i class="fa-solid fa-users-slash text-4xl mb-3 text-slate-300"></i>
                        <p class="text-lg font-medium text-slate-700">Chưa có giáo viên nào</p>
                        <p class="text-sm mt-1">Không tìm thấy giáo viên nào trong tổ có quyền "Biên soạn câu hỏi".</p>
                    </div>
                @endforelse
            </div>

            {{-- FOOTER BUTTONS --}}
            @if($teachers->count() > 0)
                <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3 sticky bottom-0 z-10">
                    <button type="reset" id="reset-btn" class="hidden px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg transition-all duration-300 font-medium flex items-center gap-2">
                        <i class="fa-solid fa-rotate-left"></i> Hủy thay đổi
                    </button>

                    <button type="submit" id="save-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg shadow-sm shadow-indigo-500/30 transition-all duration-300 flex items-center gap-2 font-medium">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu phân công
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>

<style>
    /* Làm đẹp thanh cuộn cho danh sách chuyên đề nếu nó quá dài */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
</style>

{{-- SCRIPT XỬ LÝ SỰ KIỆN --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('assignment-form');
        if(!form) return; // Nếu không có form (không có giáo viên) thì dừng

        // ==========================================
        // 1. TÍNH NĂNG CẢNH BÁO CHƯA LƯU (SMART TRACKING)
        // ==========================================
        const checkboxes = document.querySelectorAll('.topic-checkbox');
        const warningBanner = document.getElementById('unsaved-warning');
        const saveBtn = document.getElementById('save-btn');
        const resetBtn = document.getElementById('reset-btn');

        // Lưu trạng thái ban đầu
        checkboxes.forEach(cb => cb.dataset.initialChecked = cb.checked);

        function checkDirtyState() {
            let isDirty = Array.from(checkboxes).some(cb => cb.checked.toString() !== cb.dataset.initialChecked);

            if (isDirty) {
                warningBanner.classList.remove('hidden');
                resetBtn.classList.remove('hidden');
                saveBtn.classList.add('ring-4', 'ring-amber-300', 'bg-amber-600', 'hover:bg-amber-700');
                saveBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            } else {
                warningBanner.classList.add('hidden');
                resetBtn.classList.add('hidden');
                saveBtn.classList.remove('ring-4', 'ring-amber-300', 'bg-amber-600', 'hover:bg-amber-700');
                saveBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            }
            return isDirty;
        }

        checkboxes.forEach(checkbox => checkbox.addEventListener('change', checkDirtyState));

        form.addEventListener('reset', () => setTimeout(checkDirtyState, 10));

        window.addEventListener('beforeunload', function (e) {
            if (checkDirtyState()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        form.addEventListener('submit', () => window.onbeforeunload = null);

        // ==========================================
        // 2. TÍNH NĂNG LỌC CHUYÊN ĐỀ THEO LOẠI
        // ==========================================
        const topicFilters = document.querySelectorAll('.topic-filter');
        
        topicFilters.forEach(filter => {
            filter.addEventListener('change', function() {
                const teacherId = this.dataset.teacherId;
                const selectedTypeId = this.value;
                
                // Tìm tất cả các chuyên đề thuộc về giáo viên này
                const topicItems = document.querySelectorAll(`.topic-item.teacher-${teacherId}`);
                
                topicItems.forEach(item => {
                    if (selectedTypeId === 'all' || item.dataset.typeId === selectedTypeId) {
                        item.classList.remove('hidden');
                        item.classList.add('flex'); // Trả lại layout flex ban đầu
                    } else {
                        item.classList.remove('flex');
                        item.classList.add('hidden'); // Ẩn đi nếu không khớp
                    }
                });
            });
        });
    });
</script>
@endsection