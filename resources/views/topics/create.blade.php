@extends('layouts.main')

@section('title', 'Thêm Chuyên đề mới')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ request('back_url') ? urldecode(request('back_url')) : route('topics.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Thêm Chuyên đề mới</h2>
            <p class="text-slate-500 text-sm mt-1">Nhập thông tin chi tiết cho chuyên đề mới</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <form action="{{ route('topics.store') }}" method="POST" class="p-6">
            @csrf
            
            @if(request('back_url'))
                <input type="hidden" name="back_url" value="{{ request('back_url') }}">
            @endif

            <div class="mb-6">
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                    Tên chuyên đề <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white outline-none transition-all @error('name') border-rose-500 focus:ring-rose-500 @enderror"
                    placeholder="VD: Động học, Lịch sử thế giới hiện đại...">
                @error('name')
                    <p class="mt-1.5 text-sm text-rose-500"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="subject_id" class="block text-sm font-semibold text-slate-700 mb-2">
                        Môn học <span class="text-rose-500">*</span>
                    </label>
                    <select name="subject_id" id="subject_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all @error('subject_id') border-rose-500 @enderror">
                        <option value="">-- Chọn môn học --</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id', request('subject_id')) == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('subject_id')
                        <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="topic_type_id" class="block text-sm font-semibold text-slate-700 mb-2">
                        Loại chuyên đề <span class="text-rose-500">*</span>
                    </label>
                    <select name="topic_type_id" id="topic_type_id" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all @error('topic_type_id') border-rose-500 @enderror">
                        <option value="">-- Chọn loại chuyên đề --</option>
                        @foreach($topicTypes as $type)
                            <option value="{{ $type->id }}" {{ old('topic_type_id', request('topic_type_id')) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('topic_type_id')
                        <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <label for="grade" class="block text-sm font-semibold text-slate-700 mb-2">
                        Khối lớp <span class="text-rose-500">*</span>
                    </label>
                    <select name="grade" id="grade" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all">
                        <option value="">-- Chọn khối --</option>
                        <option value="10" {{ old('grade', request('grade')) == '10' ? 'selected' : '' }}>Khối 10</option>
                        <option value="11" {{ old('grade', request('grade')) == '11' ? 'selected' : '' }}>Khối 11</option>
                        <option value="12" {{ old('grade', request('grade')) == '12' ? 'selected' : '' }}>Khối 12</option>
                    </select>
                    @error('grade')
                        <p class="mt-1.5 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="order" class="block text-sm font-semibold text-slate-700 mb-2">
                        Thứ tự <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="order" id="order" value="{{ old('order', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all"
                        placeholder="VD: 1">
                </div>

                <div>
                    <label for="total_periods" class="block text-sm font-semibold text-slate-700 mb-2">
                        Số tiết học <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="total_periods" id="total_periods" value="{{ old('total_periods', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:bg-white outline-none transition-all"
                        placeholder="VD: 4">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100">
                <a href="{{ request('back_url') ? urldecode(request('back_url')) : route('topics.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-floppy-disk"></i> Lưu chuyên đề
                </button>
            </div>
        </form>
    </div>
</div>
@endsection