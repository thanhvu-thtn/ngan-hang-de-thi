<?php

namespace App\Http\Controllers;

use App\Models\QuestionType;
use Illuminate\Http\Request;
// Import 2 file Request vừa tạo vào
use App\Http\Requests\StoreQuestionTypeRequest;
use App\Http\Requests\UpdateQuestionTypeRequest;

class QuestionTypeController extends Controller
{
    public function index()
    {
        $questionTypes = QuestionType::orderBy('id', 'asc')->get();
        return view('question-types.index', compact('questionTypes'));
    }

    public function store(StoreQuestionTypeRequest $request)
    {
        QuestionType::create($request->all());
        return redirect()->route('question-types.index')->with('success', 'Thêm loại câu hỏi thành công.');
    }

    public function update(UpdateQuestionTypeRequest $request, QuestionType $questionType)
    {
        $questionType->update($request->all());
        return redirect()->route('question-types.index')->with('success', 'Cập nhật loại câu hỏi thành công.');
    }   

    public function destroy(QuestionType $questionType)
    {
        // Kiểm tra xem loại câu hỏi này đã có câu hỏi nào sử dụng chưa
        if ($questionType->questions()->count() > 0) {
            return redirect()->route('question-types.index')->with('error', 'Không thể xóa loại câu hỏi đang có dữ liệu câu hỏi liên quan.');
        }

        $questionType->delete();

        return redirect()->route('question-types.index')->with('success', 'Đã xóa loại câu hỏi.');
    }
}