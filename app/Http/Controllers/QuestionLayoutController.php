<?php

namespace App\Http\Controllers;

use App\Models\QuestionLayout;
use App\Http\Requests\StoreQuestionLayoutRequest;
use App\Http\Requests\UpdateQuestionLayoutRequest;
use Illuminate\Http\Request;

class QuestionLayoutController extends Controller
{
    public function index()
    {
        $layouts = QuestionLayout::latest()->paginate(10);
        return view('question-layouts.index', compact('layouts'));
    }

    public function create()
    {
        return view('question-layouts.create');
    }

    public function store(StoreQuestionLayoutRequest $request)
    {
        QuestionLayout::create($request->validated());

        return redirect()->route('question-layouts.index')
                         ->with('success', 'Thêm cấu hình hiển thị thành công!');
    }

    public function edit(QuestionLayout $questionLayout)
    {
        return view('question-layouts.edit', compact('questionLayout'));
    }

    public function update(UpdateQuestionLayoutRequest $request, QuestionLayout $questionLayout)
    {
        $questionLayout->update($request->validated());

        return redirect()->route('question-layouts.index')
                         ->with('success', 'Cập nhật cấu hình hiển thị thành công!');
    }

    public function destroy(QuestionLayout $questionLayout)
    {
        // Vì ở CSDL ta set ON DELETE SET NULL cho trường layout_id ở bảng questions, 
        // nên khi xoá layout, các câu hỏi cũ sẽ không bị mất mà chỉ chuyển layout về null.
        $questionLayout->delete();

        return redirect()->route('question-layouts.index')
                         ->with('success', 'Xóa cấu hình thành công!');
    }
}