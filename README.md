1. Khởi tạo Database (Migrations):

Đã thiết kế xong cấu trúc và mối quan hệ cho 5 bảng cốt lõi của hệ thống giáo dục: subjects (Môn học), topic_types (Loại chuyên đề), topics (Chuyên đề), contents (Nội dung), và objectives (Yêu cầu cần đạt).

2. Module Quản lý Môn học (subjects):

Xây dựng thành công toàn bộ luồng CRUD (Thêm, Xem, Sửa, Xóa).

Giao diện được thiết kế hiện đại, responsive bằng Tailwind CSS kết hợp icon FontAwesome.

Fix thành công lỗi giao diện (nút Lưu bị tàng hình do Tailwind JIT chưa nạp kịp class màu).

Bảo mật dữ liệu: Thêm 2 lớp bảo vệ (Frontend alert + Backend logic) để ngăn chặn việc xóa Môn học nếu bên trong nó đang chứa các Chuyên đề, giúp tránh lỗi cơ sở dữ liệu và dữ liệu rác.

3. Module Quản lý Loại chuyên đề (topic_types):

Hoàn thiện luồng CRUD bao gồm cả 2 trường thông tin là Tên loại (name) và Mô tả (description) bám sát chuẩn Database.

Sửa thành công lỗi Route (Route not defined) do sai lệch định dạng tên (_ và -) giữa định nghĩa trong web.php và lúc gọi hàm trong View.

Bảo mật dữ liệu: Áp dụng thành công logic ngăn chặn xóa Loại chuyên đề nếu đang có Chuyên đề (topic) nào đó sử dụng phân loại này.

4. Ghi chú
    - Đã cài đặt gói Spatie Laravel Permission của laravel về phân quyền
    - Kéo code của Katex, mathjax, tinyMCE về máy.
    - editor.js: Khởi tạo tinyMCE và dùng Katex hiển thị nội dung trong preview.
    ## [Cập nhật] - Ngày 29/03/2026
### ✨ Tính năng mới (Features)
- **Hệ thống phân quyền (Spatie):** - Tích hợp package `spatie/laravel-permission`.
  - Thiết lập Seeder tạo sẵn 5 Vai trò cốt lõi theo đúng đặc tả: Admin, Tổ trưởng, Biên soạn, Biên tập, Ra đề.
  - Phân chia các quyền tương ứng: truy cập hệ thống, truy cập bộ môn, thêm/sửa câu hỏi, thêm đề thi.
- **Quản lý Người dùng (Users CRUD):**
  - Thêm cột `subject_id` vào bảng `users` để liên kết giáo viên/tổ trưởng với môn học.
  - Xây dựng hoàn chỉnh Controller và các màn hình giao diện (View: `index`, `create`, `edit`) cho bảng Users.
  - Tích hợp logic tự động gán Vai trò (Role) và Môn học (Subject) ngay khi tạo hoặc cập nhật tài khoản.
  - Thêm tài khoản Admin mặc định (`admin@gmail.com`).