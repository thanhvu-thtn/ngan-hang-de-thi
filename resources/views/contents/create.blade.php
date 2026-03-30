@extends('layouts.main')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Thêm Nội dung mới</h2>
        <a href="{{ request('back_url') ? urldecode(request('back_url')) : route('contents.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
        <form action="{{ route('contents.store') }}" method="POST">
            @csrf
            
            @if(request('back_url'))
                <input type="hidden" name="back_url" value="{{ request('back_url') }}">
            @endif
            
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2">Chuyên đề <span class="text-red-500">*</span></label>
                <select name="topic_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('topic_id') border-red-500 @enderror" required>
                    <option value="">-- Chọn chuyên đề --</option>
                    @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ old('topic_id', request('topic_id')) == $topic->id ? 'selected' : '' }}>
                            {{ $topic->name }} (Khối {{ $topic->grade }})
                        </option>
                    @endforeach
                </select>
                @error('topic_id') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tên nội dung <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('name') border-red-500 @enderror" required>
                @error('name') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Số tiết <span class="text-red-500">*</span></label>
                    <input type="number" name="periods" min="0" value="{{ old('periods', 0) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('periods') border-red-500 @enderror" required>
                    @error('periods') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Thứ tự hiển thị</label>
                    <input type="number" name="order" value="{{ old('order', 1) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('order') border-red-500 @enderror">
                    @error('order') <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition duration-150">
                    <i class="fa-solid fa-floppy-disk mr-2"></i> Lưu Nội dung
                </button>
            </div>
        </form>
    </div>
</div>
@endsection