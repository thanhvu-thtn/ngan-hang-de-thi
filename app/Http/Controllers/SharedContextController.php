<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SharedContextController extends Controller
{
    //Show
    public function show($id)
    {
        // Logic để lấy dữ liệu Shared Context theo $id
        // $sharedContext = SharedContext::findOrFail($id);
        // return view('shared_contexts.show', compact('sharedContext'));
    }

    //Edit
    public function edit($id)
    {
        // Logic để lấy dữ liệu Shared Context theo $id
        // $sharedContext = SharedContext::findOrFail($id);
        // return view('shared_contexts.edit', compact('sharedContext'));
    }

    //Store
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

    //Update
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
