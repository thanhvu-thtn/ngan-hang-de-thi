<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        // 1. Kiểm tra tính hợp lệ (bắt buộc là ảnh, dung lượng tối đa 5MB)
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            // 2. Tạo tên file bằng UUID (vd: 550e8400-e29b-41d4-a716-446655440000.png)
            $filename = Str::uuid() . '.' . $extension;

            // 3. Tạo cấu trúc thư mục theo ngày (vd: uploads/2026/04/01)
            $folder = 'uploads/' . date('Y/m/d');

            // 4. Lưu ảnh vào thư mục storage/app/public/uploads/YYYY/MM/DD
            $path = $file->storeAs($folder, $filename, 'public');

            // 5. Trả về JSON chứa đường dẫn tới ảnh (TinyMCE bắt buộc phải có key "location")
            return response()->json([
                'location' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['error' => 'Không tìm thấy dữ liệu ảnh.'], 400);
    }
}
