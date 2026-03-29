<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContentRequest;
use App\Models\Content;
use App\Models\Topic;
use App\Models\TopicType;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Lấy các tham số từ URL
        $keyword = $request->input('keyword');
        $topicId = $request->input('topic_id');
        $grade = $request->input('grade');
        $topicTypeId = $request->input('topic_type_id');

        $query = Content::with('topic');

        // BẢO MẬT: Phân quyền dữ liệu theo môn học
        if (! $user->hasRole('Admin')) {
            $query->whereHas('topic', function ($q) use ($user) {
                $q->where('subject_id', $user->subject_id);
            });
        }

        // ÁP DỤNG CÁC BỘ LỌC VÀO TRUY VẤN
        $query->whereHas('topic', function ($q) use ($grade, $topicTypeId, $topicId) {
            if ($grade) {
                $q->where('grade', $grade);
            }
            if ($topicTypeId) {
                $q->where('topic_type_id', $topicTypeId);
            }
            if ($topicId) {
                $q->where('id', $topicId);
            }
        });

        if ($keyword) {
            $query->where('name', 'like', '%'.$keyword.'%');
        }

        // Phân trang và giữ nguyên URL parameters cho các nút Next, Prev của phân trang
        $contents = $query->orderBy('topic_id')->orderBy('order')->paginate(15)->appends($request->all());

        // Đổ dữ liệu ra các dropdown filter
        $topicTypes = TopicType::orderBy('name')->get();

        if ($user->hasRole('Admin')) {
            $topics = Topic::orderBy('name')->get();
        } else {
            $topics = Topic::where('subject_id', $user->subject_id)->orderBy('name')->get();
        }

        return view('contents.index', compact('contents', 'topics', 'topicTypes'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('Admin')) {
            $topics = Topic::orderBy('name')->get();
        } else {
            $topics = Topic::where('subject_id', $user->subject_id)->orderBy('name')->get();
        }

        return view('contents.create', compact('topics'));
    }

    public function store(ContentRequest $request)
    {
        $user = auth()->user();
        $validated = $request->validated();

        // BẢO VỆ 2 LỚP: Đề phòng hacker đổi ID Topic bằng F12 sang môn khác
        if (! $user->hasRole('Admin')) {
            $topic = Topic::findOrFail($validated['topic_id']);
            if ($topic->subject_id != $user->subject_id) {
                abort(403, 'Bạn không thể thêm nội dung vào chuyên đề của môn học khác!');
            }
        }

        Content::create($validated);

        return redirect()->route('contents.index', $request->query())
            ->with('success', 'Thêm nội dung thành công!');
    }

    public function edit(Content $content)
    {
        $user = auth()->user();

        // ÉP QUYỀN: Bắt buộc load quan hệ topic để check môn học
        $content->load('topic');

        if (! $user->hasRole('Admin') && $content->topic->subject_id != $user->subject_id) {
            abort(403, 'Bạn không có quyền sửa nội dung của môn học khác!');
        }

        if ($user->hasRole('Admin')) {
            $topics = Topic::orderBy('name')->get();
        } else {
            $topics = Topic::where('subject_id', $user->subject_id)->orderBy('name')->get();
        }

        return view('contents.edit', compact('content', 'topics'));
    }

    public function update(ContentRequest $request, Content $content)
    {
        $user = auth()->user();
        $validated = $request->validated();
        $content->load('topic');

        // BẢO VỆ LÚC CẬP NHẬT
        if (! $user->hasRole('Admin') && $content->topic->subject_id != $user->subject_id) {
            abort(403, 'Bạn không có quyền sửa nội dung của môn học khác!');
        }

        if (! $user->hasRole('Admin')) {
            $newTopic = Topic::findOrFail($validated['topic_id']);
            if ($newTopic->subject_id != $user->subject_id) {
                abort(403, 'Bạn không thể chuyển nội dung sang chuyên đề của môn khác!');
            }
        }

        $content->update($validated);

        // ĐIỂM ĂN TIỀN: Lấy toàn bộ tham số trên URL (?grade=10&page=2...) để gán ngược lại vào lệnh redirect
        return redirect()->route('contents.index', $request->query())
            ->with('success', 'Cập nhật nội dung thành công!');
    }

    public function destroy(Request $request, Content $content)
    {
        // Kiểm tra xem Nội dung này đã có Yêu cầu cần đạt nào chưa
        if ($content->objectives()->exists()) {
            // Nếu có, chặn không cho xóa và báo lỗi, giữ nguyên bộ lọc
            return redirect()->route('contents.index', $request->query())
                ->with('error', 'Nội dung này còn yêu cầu cần đạt, không thể xoá. Nếu muốn xoá nội dung này, bạn phải xoá hết yêu cầu cần đạt của nó trước.');
        }

        // Nếu an toàn thì tiến hành xóa
        $content->delete();

        // Trả về trang index kèm theo các thông số bộ lọc
        return redirect()->route('contents.index', $request->query())
            ->with('success', 'Xóa nội dung thành công!');
    }
}
