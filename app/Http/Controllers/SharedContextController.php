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

    // Edit
    public function edit($id) {}

    // Store
    public function store(Request $request)
    {
        // Logic để lưu Shared Context mới vào DB
        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     // Các trường khác nếu cần
        // ]);
        // SharedContext::create($validatedData);
        // return redirect()->route('shared_contexts.index')->with('success', 'Shared Context đã được tạo thành công.');
    }

    // Update
    public function update(Request $request, $id)
    {
        // Logic để cập nhật Shared Context theo $id
        // $sharedContext = SharedContext::findOrFail($id);
        // $validatedData = $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     // Các trường khác nếu cần
        // ]);
        // $sharedContext->update($validatedData);
        // return redirect()->route('shared_contexts.index')->with('success', 'Shared Context đã được cập nhật thành công.');
    }
}
