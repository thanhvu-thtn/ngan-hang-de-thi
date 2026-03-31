<?php

namespace App\Http\Controllers;

use App\Models\CognitiveLevel;
use App\Http\Requests\StoreCognitiveLevelRequest;
use App\Http\Requests\UpdateCognitiveLevelRequest;

class CognitiveLevelController extends Controller
{
    public function index()
    {
        // Sắp xếp theo level_weight tăng dần (Nhận biết -> Thông hiểu -> Vận dụng...)
        $cognitiveLevels = CognitiveLevel::orderBy('level_weight', 'asc')->get();
        return view('cognitive-levels.index', compact('cognitiveLevels'));
    }

    public function store(StoreCognitiveLevelRequest $request)
    {
        CognitiveLevel::create($request->validated());
        return redirect()->route('cognitive-levels.index')->with('success', 'Thêm mức độ nhận thức thành công.');
    }

    public function update(UpdateCognitiveLevelRequest $request, CognitiveLevel $cognitiveLevel)
    {
        $cognitiveLevel->update($request->validated());
        return redirect()->route('cognitive-levels.index')->with('success', 'Cập nhật mức độ nhận thức thành công.');
    }

    public function destroy(CognitiveLevel $cognitiveLevel)
    {
        if ($cognitiveLevel->questions()->count() > 0) {
            return redirect()->route('cognitive-levels.index')->with('error', 'Không thể xóa mức độ nhận thức đang có dữ liệu câu hỏi liên quan.');
        }

        $cognitiveLevel->delete();
        return redirect()->route('cognitive-levels.index')->with('success', 'Đã xóa mức độ nhận thức.');
    }
}