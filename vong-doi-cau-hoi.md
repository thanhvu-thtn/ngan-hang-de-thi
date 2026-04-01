BÁO CÁO: VÒNG ĐỜI CÂU HỎI & QUẢN LÝ PHÂN QUYỀN (QUESTION LIFECYCLE)
Vòng đời của một câu hỏi trong hệ thống EduBank là một chu trình khép kín, liên tục được đánh giá và tinh chỉnh để đảm bảo chất lượng đo lường. Hệ thống vận hành dựa trên 5 giai đoạn chính gắn liền với 4 nhóm quyền hạn đặc thù.

GIAI ĐOẠN 1: KHỞI TẠO (CREATION)
Hành động: Câu hỏi được đưa vào hệ thống thông qua việc nhập liệu thủ công trên Form hoặc Import hàng loạt từ file Word.

Trạng thái hệ thống: status = 0 (Nháp / Chờ duyệt - Nhãn Đỏ).

Quyền hạn liên quan: Người dùng có quyền tạo câu hỏi (Giáo viên bộ môn). Ở giai đoạn này, câu hỏi hoàn toàn bị "đóng băng" đối với các module ra đề thi.

GIAI ĐOẠN 2: THẨM ĐỊNH (APPROVAL)
Hành động: Người quản lý chuyên môn kiểm tra nội dung, hình thức, đáp án, lời giải và ma trận kiến thức của câu hỏi.

Quyết định:

Từ chối: Đánh trạng thái status = 2 (Cần sửa). Trả về cho người tạo để chỉnh sửa.

Chấp nhận: Đánh trạng thái status = 1 (Đạt chuẩn - Nhãn Xanh). Cập nhật trường checker_id (người duyệt) và checked_at (thời gian duyệt).

Quyền hạn liên quan: quyen-tham-dinh (Tổ trưởng chuyên môn / Chuyên gia thẩm định).

Trạng thái hệ thống: Câu hỏi chính thức gia nhập "Ngân hàng an toàn" và sẵn sàng được sử dụng.

GIAI ĐOẠN 3: SỬ DỤNG (DEPLOYMENT)
Hành động: Câu hỏi được hệ thống thuật toán (hoặc người dùng) bốc ngẫu nhiên/có chủ đích dựa trên ma trận đề thi để tạo thành các Đề thi trắc nghiệm.

Quyền hạn liên quan: quyen-ra-de (Giáo viên ra đề / Ban khảo thí).

Lưu ý: Chỉ những câu hỏi có status = 1 mới xuất hiện trong tầm nhìn của người có quyền ra đề.

GIAI ĐOẠN 4: ĐO LƯỜNG & PHÂN TÍCH (MEASUREMENT & ANALYSIS)
Hành động: Sau khi học sinh hoàn thành bài thi trên giấy hoặc nền tảng khác, dữ liệu làm bài (đáp án học sinh chọn) được đẩy ngược vào hệ thống.

Hệ thống tự động xử lý:

Cập nhật bảng question_statistics.

Tính toán lại difficulty_index (Độ khó thực tế dựa trên tỷ lệ làm đúng).

Tính toán reliability (Độ tin cậy) và validity (Độ phân biệt).

Quyền hạn liên quan: quyen-cham-thi (Ban khảo thí / Người phụ trách nhập liệu điểm).

GIAI ĐOẠN 5: CẢI TIẾN (REVISION & LOOP)
Hành động: Dựa trên các chỉ số thống kê (Ví dụ: một câu hỏi quá khó hoặc có độ phân biệt âm - học sinh giỏi làm sai nhiều hơn học sinh kém), chuyên gia sẽ quyết định số phận của câu hỏi.

Giữ nguyên: Nếu các chỉ số đẹp.

Chỉnh sửa: Thay đổi lời dẫn hoặc các phương án nhiễu.

Quyền hạn liên quan: quyen-sua-cau-hoi (Chuyên gia phát triển chương trình).

Chu trình lặp: Ngay khi câu hỏi bị chỉnh sửa (Update), trạng thái của nó lập tức bị reset về status = 0 (Nhãn Đỏ). Nó bắt buộc phải quay lại Giai đoạn 2 để ông có quyen-tham-dinh kiểm tra lại lần nữa.