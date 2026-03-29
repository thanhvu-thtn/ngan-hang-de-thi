<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\TopicType;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Nhận các tham số lọc từ URL
        $keyword = $request->input('keyword');
        $subjectId = $request->input('subject_id');
        $grade = $request->input('grade');
        $topicTypeId = $request->input('topic_type_id');

        $query = Topic::select('topics.*')
            ->leftJoin('subjects', 'topics.subject_id', '=', 'subjects.id')
            ->leftJoin('topic_types', 'topics.topic_type_id', '=', 'topic_types.id')
            ->with(['subject', 'topicType']);

        // --- BẮT ĐẦU BỘ LỌC PHÂN QUYỀN BẢO MẬT ---
        if (! $user->hasRole('Admin')) {
            // Nếu KHÔNG phải Admin: Ép buộc chỉ truy xuất chuyên đề thuộc môn của mình
            $query->where('topics.subject_id', $user->subject_id);

            // Gán đè biến này để nếu ngoài View bạn có dùng $request->subject_id
            // để giữ trạng thái "selected" của dropdown thì nó vẫn hoạt động đúng.
            $subjectId = $user->subject_id;
        } else {
            // Nếu là Admin: Cho phép dùng bộ lọc môn học từ giao diện như bình thường
            if ($subjectId) {
                $query->where('topics.subject_id', $subjectId);
            }
        }
        // --- KẾT THÚC BỘ LỌC PHÂN QUYỀN ---

        // 2. Áp dụng các điều kiện lọc còn lại
        if ($keyword) {
            $query->where('topics.name', 'like', '%'.$keyword.'%');
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

        // 4. Lấy dữ liệu cho các Select Box ở View
        // Nếu là Admin thì lấy hết môn, nếu là Giáo viên/Tổ trưởng thì chỉ lấy đúng môn của họ
        if ($user->hasRole('Admin')) {
            $subjects = Subject::orderBy('name')->get();
        } else {
            $subjects = Subject::where('id', $user->subject_id)->get();
        }

        $topicTypes = TopicType::orderBy('name')->get();

        return view('topics.index', compact('topics', 'subjects', 'topicTypes'));
    }

    public function create()
    {
        $user = auth()->user();

        // Nếu là Admin lấy toàn bộ, ngược lại chỉ lấy môn của user
        if ($user->hasRole('Admin')) {
            $subjects = Subject::orderBy('name')->get();
        } else {
            $subjects = Subject::where('id', $user->subject_id)->get();
        }

        $topicTypes = TopicType::orderBy('name')->get();

        return view('topics.create', compact('subjects', 'topicTypes'));
    }

    public function store(TopicRequest $request)
    {
        $user = auth()->user();

        // Dữ liệu đã tự động được lọc sạch sẽ, ta chỉ việc lấy ra dùng!
        $validated = $request->validated();

        if (! $user->hasRole('Admin')) {
            $validated['subject_id'] = $user->subject_id;
        }

        Topic::create($validated);

        return redirect()->route('topics.index')
            ->with('success', 'Đã thêm chuyên đề thành công!');
    }

    public function edit(Topic $topic)
    {
        $user = auth()->user();

        // BỨC TƯỜNG LỬA: Nếu không phải Admin và ID môn học không khớp -> Báo lỗi 403 ngay!
        if (! $user->hasRole('Admin') && $topic->subject_id != $user->subject_id) {
            abort(403, 'Bạn không có quyền chỉnh sửa chuyên đề của môn học khác!');
        }

        if ($user->hasRole('Admin')) {
            $subjects = Subject::orderBy('name')->get();
        } else {
            $subjects = Subject::where('id', $user->subject_id)->get();
        }

        $topicTypes = TopicType::orderBy('name')->get();

        return view('topics.edit', compact('topic', 'subjects', 'topicTypes'));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $user = auth()->user();

        // BẢO VỆ 2 LỚP
        if (! $user->hasRole('Admin') && $topic->subject_id != $user->subject_id) {
            abort(403, 'Bạn không có quyền cập nhật chuyên đề của môn học khác!');
        }

        $rules = [
            'name' => 'required|string|max:255',
            'topic_type_id' => 'required|exists:topic_types,id',
            'grade' => 'required|integer',
            'order' => 'nullable|integer',
        ];

        if ($user->hasRole('Admin')) {
            $rules['subject_id'] = 'required|exists:subjects,id';
        }

        $validated = $request->validate($rules);

        // Gán lại subject_id an toàn
        if (! $user->hasRole('Admin')) {
            $validated['subject_id'] = $user->subject_id;
        }

        $topic->update($validated);

        return redirect()->route('topics.index')
            ->with('success', 'Cập nhật chuyên đề thành công!');
    }

    public function destroy(Topic $topic)
    {
        $user = auth()->user();

        // Chặn người dùng xóa nhầm/cố tình xóa bài môn khác
        if (! $user->hasRole('Admin') && $topic->subject_id != $user->subject_id) {
            abort(403, 'Bạn không có quyền xóa chuyên đề của môn học khác!');
        }

        $topic->delete();

        return redirect()->route('topics.index')
            ->with('success', 'Đã xóa chuyên đề thành công!');
    }
}
