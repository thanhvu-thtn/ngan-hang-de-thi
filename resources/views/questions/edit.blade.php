@extends('layouts.main')

@section('content')
<div class="container mx-auto p-6 max-w-5xl">
    
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-amber-500 rounded-lg shadow-lg shadow-amber-200">
                    <i class="fa-solid fa-pen text-white text-sm"></i>
                </div>
                Chỉnh sửa thiết lập câu hỏi
            </h2>
            <p class="text-slate-500 text-sm mt-1">Cập nhật thông tin cơ bản và mục tiêu đánh giá.</p>
        </div>
        <a href="{{ route('questions.index') }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 font-medium transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    {{-- Form --}}
    <form action="{{ route('questions.update', ['question' => $question->id]) }}" method="POST" id="question-edit-form">
        @csrf
        @method('PUT')

        {{-- KHỐI 1: THÔNG TIN CƠ BẢN --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2">Thông tin cơ bản</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Mã câu hỏi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mã câu hỏi (Tag Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="tag_name" value="{{ old('tag_name', $question->tag_name) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" required>
                </div>

                {{-- Tên/Tiêu đề --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tên gợi nhớ (Name) <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $question->name) }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" required>
                </div>

                {{-- Loại câu hỏi (Bị khóa) --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Loại câu hỏi <span class="text-xs font-normal text-slate-500">(Không được đổi)</span></label>
                    <input type="text" value="{{ $question->questionType->name ?? 'N/A' }}" class="w-full px-4 py-2.5 bg-slate-200 border border-slate-300 text-slate-500 rounded-xl cursor-not-allowed" readonly disabled>
                    <input type="hidden" name="question_type_id" value="{{ $question->question_type_id }}">
                </div>

                {{-- Mức độ nhận thức --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mức độ nhận thức <span class="text-rose-500">*</span></label>
                    <select name="cognitive_level_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all" required>
                        @foreach($cognitiveLevels as $level)
                            <option value="{{ $level->id }}" {{ old('cognitive_level_id', $question->cognitive_level_id) == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- KHỐI 2: MỤC TIÊU ĐÁNH GIÁ (GỌI COMPONENT TREEVIEW) --}}
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-2">Mục tiêu đánh giá <span class="text-rose-500">*</span></h3>
            
            {{-- CHÚ Ý ĐƯỜNG DẪN INCLUDE: Hãy trỏ đúng vào thư mục chứa file treeview.blade.php của bạn --}}
            @include('questions.partials.treeview', [
                'treeByGrade' => $treeByGrade,
                'inputName' => 'objective_ids[]',
                'showCount' => false,
                'selectedIds' => $selectedObjectiveIds 
            ])
            
            @error('objective_ids')
                <span class="text-sm text-rose-500 mt-2 block">{{ $message }}</span>
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-3 pt-4 sticky bottom-4">
            <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition shadow-lg shadow-blue-200 flex items-center gap-2">
                Lưu thiết lập & Đi tiếp <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>

    </form>
</div>

{{-- GỌI FILE JS XỬ LÝ TREEVIEW --}}
@include('questions.partials.treeview_js')

@endsection