BÁO CÁO KIẾN TRÚC CLASS & INTERFACE (MODULE QUESTION)
1. Nhóm Giao diện chuẩn hóa (Interfaces)
Đây là các "bản hợp đồng" bắt buộc các lớp thực thi phải tuân thủ, đảm bảo tính nhất quán của hệ thống.
•	QuestionHandlerInterface (Vị trí: app/QuestionHandlers/)
o	Vai trò: Định nghĩa 4 hành động cốt lõi mà mọi loại câu hỏi (Trắc nghiệm, Tự luận...) đều phải có.
o	Các phương thức (Methods):
	validateData(Request $request): array -> Kiểm tra dữ liệu từ form gửi lên hợp lệ hay không.
	store(array $validatedData): Question -> Lưu dữ liệu câu hỏi mới vào Database.
	update(Question $question, array $validatedData): Question -> Cập nhật dữ liệu câu hỏi đã có.
	getDetails(Question $question): array -> Truy xuất dữ liệu chi tiết của câu hỏi kèm các quan hệ liên quan (đáp án, lời giải...).
2. Nhóm Lớp xử lý nghiệp vụ câu hỏi (Handlers)
Dựa theo hình ảnh cây thư mục bạn đã tạo, đây là các "đứa con" thực thi trực tiếp QuestionHandlerInterface. Mỗi lớp sẽ chịu trách nhiệm bóc tách và lưu trữ dữ liệu đặc thù của riêng nó.
•	MultipleChoiceHandler (Trắc nghiệm nhiều lựa chọn)
o	Đặc thù: Xử lý câu hỏi có nhiều đáp án (bảng question_choices), kiểm tra bắt buộc phải có ít nhất 1 đáp án đúng.
•	TrueFalseHandler (Trắc nghiệm Đúng/Sai)
o	Đặc thù: Ràng buộc chặt chẽ chỉ có 2 lựa chọn (Đúng và Sai), xử lý logic lưu đáp án chuyên biệt.
•	ShortAnswerHandler (Trắc nghiệm trả lời ngắn)
o	Đặc thù: Xử lý việc đối chiếu từ khóa (keywords) hoặc giá trị chính xác do học sinh điền vào.
•	EssayHandler (Tự luận)
o	Đặc thù: Bỏ qua bảng question_choices, chỉ tập trung vào phần thân câu hỏi (stem) và tiêu chí chấm điểm/lời giải chi tiết (question_explanations).
•	SharedContextHandler (Xử lý ngữ cảnh chung)
o	Đặc thù: Không hẳn là một câu hỏi độc lập, mà dùng để lưu trữ và xử lý các đoạn văn bản/hình ảnh dùng chung cho một chùm câu hỏi (ví dụ: bài đọc hiểu Tiếng Anh).
(Lưu ý: Để gọi đúng các Handler này một cách linh hoạt, hệ thống sẽ sử dụng thêm một lớp QuestionHandlerFactory nhằm dựa vào loại câu hỏi để "sinh ra" đúng Handler tương ứng).
3. Nhóm Lớp Dịch vụ hệ thống (Services)
Đây là các lớp hoạt động độc lập, không trực tiếp thao tác với Database mà đứng ra nhận nhiệm vụ nặng nhọc, giao tiếp với phần mềm bên ngoài hoặc xử lý logic phức tạp. Áp dụng chuẩn Single Responsibility (Đơn trách nhiệm).
•	QuestionExportService (Dịch vụ Xuất dữ liệu)
o	Vai trò: Đảm nhiệm việc "vẽ" dữ liệu ra các định dạng khác nhau. Gọi hàm getDetails() từ Handler để lấy dữ liệu sạch.
o	Các phương thức (Methods):
	preview(): Render giao diện HTML để xem trước câu hỏi.
	exportPdf(): Chuyển đổi HTML sang file PDF.
	exportWord(): Sử dụng Pandoc/thư viện để xuất ra định dạng .docx chuẩn chỉnh, giữ nguyên công thức toán.
•	QuestionImportService (Dịch vụ Nhập dữ liệu)
o	Vai trò: Xử lý việc upload file Word (.docx) của giáo viên, bóc tách công thức toán học và hình ảnh, sau đó chuyển thành dữ liệu chuẩn để lưu vào DB.
o	Các phương thức (Methods):
	importFromDocx(): Luồng chính nhận file và điều phối quá trình import.
	runPandoc(): Tương tác với phần mềm Pandoc qua Command Line để chuyển Docx sang HTML kèm media.
	parseHtmlToRawQuestions(): Dùng Regex cắt nhỏ HTML thành từng khối câu hỏi.
	detectQuestionType() & extractQuestionData(): Phân tích nhận diện loại câu hỏi, bóc tách lời dẫn, đáp án, và đẩy sang Handler tương ứng để lưu (store).
4. Dòng chảy dữ liệu (Workflow) tóm tắt
1.	Khi tạo thủ công: Controller nhận dữ liệu -> Gọi QuestionHandlerFactory -> Lấy Handler tương ứng -> Chạy validateData() -> Chạy store() -> Lưu DB.
2.	Khi Import file Word: Controller nhận file -> Ném cho QuestionImportService -> Pandoc chuyển thành HTML -> Regex băm nhỏ dữ liệu -> Gọi Handler -> Chạy store() -> Lưu DB.
3.	Khi Xem/Xuất file: Controller nhận lệnh -> Ném cho QuestionExportService -> Gọi Handler chạy getDetails() -> Xử lý định dạng -> Trả về View/PDF/Word.

