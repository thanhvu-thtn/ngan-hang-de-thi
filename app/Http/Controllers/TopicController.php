<?php

namespace App\Http\Controllers;

use App\Models\Topic;
use App\Models\Subject;
use App\Models\TopicType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        // 1. Nhận các tham số lọc từ URL
        $keyword = $request->input('keyword'); // Thêm dòng này để lấy từ khóa
        $subjectId = $request->input('subject_id');
        $grade = $request->input('grade');
        $topicTypeId = $request->input('topic_type_id');

        $query = Topic::select('topics.*')
            ->leftJoin('subjects', 'topics.subject_id', '=', 'subjects.id')
            ->leftJoin('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            ->with(['subject', 'topicType']); 

        // 2. Áp dụng các điều kiện lọc
        if ($keyword) {
            // Tìm kiếm tương đối (LIKE) trong Tên chuyên đề
            $query->where('topics.name', 'like', '%' . $keyword . '%');
        }
        if ($subjectId) {
            $query->where('topics.subject_id', $subjectId);
        }
        if ($grade) {
            $query->where('topics.grade', $grade);
        }
        if ($topicTypeId) {
            $query->where('topics.topic_type_id', $topicTypeId);
        }

        // 3. Sắp xếp
        $query->orderBy('subjects.name', 'asc')
              ->orderBy('topics.grade', 'asc')
              ->orderBy('topic_types.name', 'asc')
              ->orderBy('topics.order', 'asc');

        $topics = $query->paginate(15)->appends($request->all());

        $subjects = Subject::orderBy('name')->get();
        $topicTypes = TopicType::orderBy('name')->get();
            
        return view('topics.index', compact('topics', 'subjects', 'topicTypes'));
    }

    public function create()
    {
        // Lấy dữ liệu 2 bảng kia truyền sang form dropdown
        $subjects = Subject::orderBy('name')->get();
        $topicTypes = TopicType::orderBy('name')->get();
        
        return view('topics.create', compact('subjects', 'topicTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_type_id' => 'required|exists:topic_types,id',
            'name' => 'required|string|max:255',
            'grade' => 'required|integer|in:10,11,12', // Chỉ cho phép nhập khối 10, 11, 12
            'order' => 'required|integer|min:1',
            'total_periods' => 'required|integer|min:1',
        ], [
            'subject_id.required' => 'Vui lòng chọn môn học.',
            'topic_type_id.required' => 'Vui lòng chọn loại chuyên đề.',
            'name.required' => 'Vui lòng nhập tên chuyên đề.',
            'grade.in' => 'Khối lớp chỉ có thể là 10, 11 hoặc 12.',
        ]);

        Topic::create($request->all());

        return redirect()->route('topics.index')->with('success', 'Thêm chuyên đề thành công!');
    }

    public function edit(Topic $topic)
    {
        $subjects = Subject::orderBy('name')->get();
        $topicTypes = TopicType::orderBy('name')->get();
        
        return view('topics.edit', compact('topic', 'subjects', 'topicTypes'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'topic_type_id' => 'required|exists:topic_types,id',
            'name' => 'required|string|max:255',
            'grade' => 'required|integer|in:10,11,12',
            'order' => 'required|integer|min:1',
            'total_periods' => 'required|integer|min:1',
        ]);

        $topic->update($request->all());

        return redirect()->route('topics.index')->with('success', 'Cập nhật chuyên đề thành công!');
    }

    public function destroy(Topic $topic)
    {
        // Kiểm tra xem chuyên đề có đang chứa Nội dung (contents) nào không
        $hasContents = DB::table('contents')->where('topic_id', $topic->id)->exists();

        if ($hasContents) {
            return redirect()->route('topics.index')->with('error', 'Xin lỗi, không thể xoá vì chuyên đề này đang chứa các nội dung bài học. Vui lòng xoá nội dung trước.');
        }

        $topic->delete();
        return redirect()->route('topics.index')->with('success', 'Xóa chuyên đề thành công!');
    }
}