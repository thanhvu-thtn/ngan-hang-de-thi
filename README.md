# Ngân Hàng Đề Thi

Hệ thống quản lý câu hỏi và đề thi trực tuyến, hỗ trợ tạo câu hỏi trắc nghiệm, quản lý theo môn học, tạo đề thi và thi thử với tính điểm tự động.

## Tính năng

- 📚 **Quản lý môn học** – Thêm, sửa, xóa môn học
- ❓ **Ngân hàng câu hỏi** – Câu hỏi trắc nghiệm nhiều lựa chọn, phân loại theo môn học và độ khó (dễ / trung bình / khó)
- 📝 **Tạo đề thi** – Soạn đề thi từ ngân hàng câu hỏi, thiết lập thời gian làm bài
- ⏱️ **Làm bài thi** – Đếm ngược thời gian, nộp bài và xem kết quả chi tiết ngay lập tức

## Cài đặt

```bash
# 1. Cài thư viện
pip install -r requirements.txt

# 2. Chạy ứng dụng
python app.py
```

Sau đó mở trình duyệt và truy cập **http://localhost:5000**.

## Cấu trúc dự án

```
├── app.py              # Flask application & routes
├── models.py           # SQLAlchemy data models
├── requirements.txt    # Python dependencies
├── templates/          # Jinja2 HTML templates
│   ├── base.html
│   ├── index.html
│   ├── subjects.html / subject_form.html
│   ├── questions.html / question_form.html
│   ├── exams.html / exam_form.html
│   ├── take_exam.html
│   └── exam_result.html
└── static/
    ├── css/style.css
    └── js/main.js
```

## Công nghệ sử dụng

- **Backend:** Python 3 + Flask + SQLAlchemy + SQLite
- **Frontend:** Bootstrap 5 + Bootstrap Icons
