<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    // 1. Hiển thị danh sách Môn học
    public function index()
    {
        // Lấy danh sách môn học, phân trang 10 dòng/trang
        $subjects = Subject::paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    // 2. Hiển thị form Thêm mới
    public function create()
    {
        return view('subjects.create');
    }

    // 3. Xử lý lưu Thêm mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name',
        ], [
            'name.required' => 'Vui lòng nhập tên môn học.',
            'name.unique' => 'Môn học này đã tồn tại.'
        ]);

        Subject::create($request->all());

        return redirect()->route('subjects.index')->with('success', 'Thêm môn học thành công!');
    }

    // 4. Hiển thị form Sửa
    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    // 5. Xử lý lưu Cập nhật
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
        ], [
            'name.required' => 'Vui lòng nhập tên môn học.',
            'name.unique' => 'Môn học này đã tồn tại.'
        ]);

        $subject->update($request->all());

        return redirect()->route('subjects.index')->with('success', 'Cập nhật môn học thành công!');
    }

    // 6. Xử lý Xóa
    public function destroy(Subject $subject)
    {
        // Kiểm tra xem có chuyên đề nào đang liên kết với môn học này không
        $hasTopics = \DB::table('topics')->where('subject_id', $subject->id)->exists();

        // Nếu có, đẩy về trang index kèm theo thông báo lỗi
        if ($hasTopics) {
            return redirect()->route('subjects.index')->with('error', 'Xin lỗi không thể xoá vì môn học này còn có các chuyên đề. Nếu bạn muốn xoá môn học này thì phải xoá hết các chuyên đề của nó.');
        }

        // Nếu an toàn, tiến hành xóa
        $subject->delete();
        
        return redirect()->route('subjects.index')->with('success', 'Xóa môn học thành công!');
    }
}