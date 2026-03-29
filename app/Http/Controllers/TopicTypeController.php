<?php

namespace App\Http\Controllers;

use App\Models\TopicType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicTypeController extends Controller
{
    public function index()
    {
        $topicTypes = TopicType::paginate(10);
        return view('topic-types.index', compact('topicTypes'));
    }

    public function create()
    {
        return view('topic-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:topic_types,name',
            'description' => 'nullable|string|max:255', // Thêm dòng này
        ], [
            'name.required' => 'Vui lòng nhập tên loại chuyên đề.',
            'name.unique' => 'Loại chuyên đề này đã tồn tại.',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự.'
        ]);

        TopicType::create($request->all());

        return redirect()->route('topic-types.index')->with('success', 'Thêm loại chuyên đề thành công!');
    }

    public function edit(TopicType $topicType)
    {
        return view('topic-types.edit', compact('topicType'));
    }

    public function update(Request $request, TopicType $topicType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:topic_types,name,' . $topicType->id,
            'description' => 'nullable|string|max:255', // Thêm dòng này
        ], [
            'name.required' => 'Vui lòng nhập tên loại chuyên đề.',
            'name.unique' => 'Loại chuyên đề này đã tồn tại.',
            'description.max' => 'Mô tả không được vượt quá 255 ký tự.'
        ]);

        $topicType->update($request->all());

        return redirect()->route('topic-types.index')->with('success', 'Cập nhật loại chuyên đề thành công!');
    }

    public function destroy(TopicType $topicType)
    {
        $hasTopics = DB::table('topics')->where('topic_type_id', $topicType->id)->exists();

        if ($hasTopics) {
            return redirect()->route('topic-types.index')->with('error', 'Xin lỗi, không thể xoá vì loại chuyên đề này đang được sử dụng.');
        }

        $topicType->delete();
        return redirect()->route('topic-types.index')->with('success', 'Xóa loại chuyên đề thành công!');
    }
}