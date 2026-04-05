<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Objective;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    public function index(Request $request)
    {
        // Bắt buộc phải có content_id
        $contentId = $request->get('content_id');

        if (! $contentId) {
            return redirect()->route('contents.index')->with('error', 'Vui lòng chọn một nội dung cụ thể.');
        }

        $content = Content::with('topic')->findOrFail($contentId);

        // Lấy danh sách objectives của riêng nội dung này
        $objectives = Objective::where('content_id', $contentId)
            ->orderBy('id', 'asc')
            ->get();

        return view('objectives.index', compact('objectives', 'content'));
    }

    // Các hàm create, store, edit, update, destroy tạm thời để trống, ta sẽ làm sau
    public function create(Request $request)
    {
        $contentId = $request->get('content_id');
        $content = Content::with('topic')->findOrFail($contentId);

        return view('objectives.create', compact('content'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'content_id' => 'required|exists:contents,id',
            'tag_name' => 'required|unique:objectives,tag_name'
        ]);

        Objective::create($request->all());

        return redirect()->route('objectives.index', ['content_id' => $request->content_id])
            ->with('success', 'Thêm yêu cầu cần đạt thành công!');
    }

    // Cập nhật dữ liệu
    public function edit(Objective $objective)
    {
        // Lấy thông tin nội dung để hiển thị tiêu đề và nút quay lại
        $content = Content::with('topic')->findOrFail($objective->content_id);

        return view('objectives.edit', compact('objective', 'content'));
    }

    public function update(Request $request, Objective $objective)
    {
        $request->validate([
            'description' => 'required',
            'tag_name' => 'required|unique:objectives,tag_name,' . $objective->id
        ]);

        $objective->update($request->all());

        return redirect()->route('objectives.index', ['content_id' => $objective->content_id])
            ->with('success', 'Cập nhật yêu cầu cần đạt thành công!');
    }

    // Xóa dữ liệu
    public function destroy(Objective $objective)
    {
        // Lưu lại content_id trước khi xóa để biết đường quay về trang danh sách đúng bài học
        $contentId = $objective->content_id;

        // Thực hiện xóa
        $objective->delete();

        // Quay lại trang danh sách kèm thông báo thành công
        return redirect()->route('objectives.index', ['content_id' => $contentId])
            ->with('success', 'Đã xóa yêu cầu cần đạt thành công!');
    }
}
