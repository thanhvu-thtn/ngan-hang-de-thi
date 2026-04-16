<?php

namespace App\Http\Controllers;

use App\Models\SharedContext;
use Illuminate\Http\Request;

class SharedContextController extends Controller
{
    /**
     * Hiển thị danh sách dữ liệu dùng chung
     */
    public function index(Request $request)
    {
        $query = SharedContext::withCount('questions');

        // Tìm kiếm theo mã hoặc nội dung
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tag_name', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $contexts = $query->latest()->paginate(10);

        return view('shared_contexts.index', compact('contexts'));
    }

    // Show
    public function show($id)
    {
        // Eager load toàn bộ cây dữ liệu
        $context = SharedContext::with([
            'questions.cognitiveLevel',
            'questions.objectives',
            'questions.choices',
            'questions.questionType',
        ])->findOrFail($id);

        return view('shared_contexts.show', compact('context'));
    }

    // Create - Hiển thị form tạo mới
    public function create()
    {
        return view('shared_contexts.create');
    }

    // Store - Lưu dữ liệu vào Database
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $validated = $request->validate([
            'tag_name' => 'required|string|max:100|unique:shared_contexts,tag_name',
            'content' => 'required|string',
            'note' => 'nullable|string',
        ], [
            'tag_name.required' => 'Mã định danh không được để trống.',
            'tag_name.unique' => 'Mã định danh này đã tồn tại.',
            'content.required' => 'Nội dung văn bản không được để trống.',
        ]);

        // 2. Tạo mới bản ghi
        $context = SharedContext::create($validated);

        // 3. Chuyển hướng sang trang show của chính nó kèm thông báo
        return redirect()->route('shared-contexts.show', $context->id)
            ->with('success', 'Đã tạo dữ liệu dùng chung thành công!');
    }

    // Update
    // Edit - Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $context = SharedContext::findOrFail($id);

        return view('shared_contexts.edit', compact('context'));
    }

    // Update - Cập nhật dữ liệu vào Database
    public function update(Request $request, $id)
    {
        $context = SharedContext::findOrFail($id);

        // 1. Validate dữ liệu
        // Lưu ý: unique:shared_contexts,tag_name,'.$id để bỏ qua chính ID hiện tại
        $validated = $request->validate([
            'tag_name' => 'required|string|max:100|unique:shared_contexts,tag_name,'.$id,
            'content' => 'required|string',
            'note' => 'nullable|string',
        ], [
            'tag_name.required' => 'Mã định danh không được để trống.',
            'tag_name.unique' => 'Mã định danh này đã tồn tại.',
            'content.required' => 'Nội dung văn bản không được để trống.',
        ]);

        // 2. Cập nhật bản ghi
        $context->update($validated);

        // 3. Chuyển hướng về trang show kèm thông báo thành công
        return redirect()->route('shared-contexts.show', $context->id)
            ->with('success', 'Đã cập nhật dữ liệu dùng chung thành công!');
    }

    public function destroy($id)
    {
        $context = SharedContext::findOrFail($id);

        // 1. Kiểm tra xem có câu hỏi nào đang dùng shared context này không
        // Dùng exists() để query thẳng xuống DB cho nhẹ, thay vì load hết list câu hỏi lên
        if ($context->questions()->exists()) {
            return redirect()->back()->with('error', 'Không thể xóa! Dữ liệu dùng chung này vẫn còn câu hỏi bên trong. Vui lòng xóa hết các câu hỏi đó trước.');
        }

        // 2. Nếu an toàn (không có câu hỏi), tiến hành xóa
        $context->delete();

        // 3. Chuyển hướng về danh sách kèm thông báo
        return redirect()->route('shared-contexts.index')
            ->with('success', 'Đã xóa dữ liệu dùng chung thành công!');
    }
}
