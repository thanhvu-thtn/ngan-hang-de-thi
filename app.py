import os
from flask import Flask, render_template, request, redirect, url_for, jsonify, flash
from models import db, Subject, Question, Choice, Exam, ExamQuestion

app = Flask(__name__)
app.config["SECRET_KEY"] = os.environ.get("SECRET_KEY", "ngan-hang-de-thi-secret")
app.config["SQLALCHEMY_DATABASE_URI"] = os.environ.get(
    "DATABASE_URL", "sqlite:///ngan_hang_de_thi.db"
)
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = False

db.init_app(app)


with app.app_context():
    db.create_all()


# ─── Home ────────────────────────────────────────────────────────────────────

@app.route("/")
def index():
    subject_count = Subject.query.count()
    question_count = Question.query.count()
    exam_count = Exam.query.count()
    recent_exams = Exam.query.order_by(Exam.created_at.desc()).limit(5).all()
    return render_template(
        "index.html",
        subject_count=subject_count,
        question_count=question_count,
        exam_count=exam_count,
        recent_exams=recent_exams,
    )


# ─── Subjects ────────────────────────────────────────────────────────────────

@app.route("/subjects")
def subjects():
    subjects = Subject.query.order_by(Subject.name).all()
    return render_template("subjects.html", subjects=subjects)


@app.route("/subjects/add", methods=["GET", "POST"])
def add_subject():
    if request.method == "POST":
        name = request.form.get("name", "").strip()
        description = request.form.get("description", "").strip()
        if not name:
            flash("Tên môn học không được để trống.", "danger")
            return render_template("subject_form.html", subject=None)
        subject = Subject(name=name, description=description)
        db.session.add(subject)
        db.session.commit()
        flash(f'Đã thêm môn học "{name}".', "success")
        return redirect(url_for("subjects"))
    return render_template("subject_form.html", subject=None)


@app.route("/subjects/<int:subject_id>/edit", methods=["GET", "POST"])
def edit_subject(subject_id):
    subject = Subject.query.get_or_404(subject_id)
    if request.method == "POST":
        name = request.form.get("name", "").strip()
        description = request.form.get("description", "").strip()
        if not name:
            flash("Tên môn học không được để trống.", "danger")
            return render_template("subject_form.html", subject=subject)
        subject.name = name
        subject.description = description
        db.session.commit()
        flash(f'Đã cập nhật môn học "{name}".', "success")
        return redirect(url_for("subjects"))
    return render_template("subject_form.html", subject=subject)


@app.route("/subjects/<int:subject_id>/delete", methods=["POST"])
def delete_subject(subject_id):
    subject = Subject.query.get_or_404(subject_id)
    name = subject.name
    db.session.delete(subject)
    db.session.commit()
    flash(f'Đã xóa môn học "{name}".', "success")
    return redirect(url_for("subjects"))


# ─── Questions ───────────────────────────────────────────────────────────────

@app.route("/questions")
def questions():
    subject_id = request.args.get("subject_id", type=int)
    difficulty = request.args.get("difficulty", "")
    query = Question.query
    if subject_id:
        query = query.filter_by(subject_id=subject_id)
    if difficulty:
        query = query.filter_by(difficulty=difficulty)
    questions = query.order_by(Question.created_at.desc()).all()
    subjects = Subject.query.order_by(Subject.name).all()
    return render_template(
        "questions.html",
        questions=questions,
        subjects=subjects,
        selected_subject=subject_id,
        selected_difficulty=difficulty,
    )


@app.route("/questions/add", methods=["GET", "POST"])
def add_question():
    subjects = Subject.query.order_by(Subject.name).all()
    if request.method == "POST":
        subject_id = request.form.get("subject_id", type=int)
        content = request.form.get("content", "").strip()
        difficulty = request.form.get("difficulty", "medium")
        choices_content = request.form.getlist("choice_content")
        correct_index = request.form.get("correct_choice", type=int)

        if not subject_id or not content:
            flash("Vui lòng điền đầy đủ thông tin câu hỏi.", "danger")
            return render_template("question_form.html", question=None, subjects=subjects)

        valid_choices = [c.strip() for c in choices_content if c.strip()]
        if len(valid_choices) < 2:
            flash("Câu hỏi cần ít nhất 2 đáp án.", "danger")
            return render_template("question_form.html", question=None, subjects=subjects)

        if correct_index is None or correct_index >= len(valid_choices):
            flash("Vui lòng chọn đáp án đúng.", "danger")
            return render_template("question_form.html", question=None, subjects=subjects)

        question = Question(subject_id=subject_id, content=content, difficulty=difficulty)
        db.session.add(question)
        db.session.flush()

        for i, choice_content in enumerate(valid_choices):
            choice = Choice(
                question_id=question.id,
                content=choice_content,
                is_correct=(i == correct_index),
            )
            db.session.add(choice)

        db.session.commit()
        flash("Đã thêm câu hỏi mới.", "success")
        return redirect(url_for("questions"))

    return render_template("question_form.html", question=None, subjects=subjects)


@app.route("/questions/<int:question_id>/edit", methods=["GET", "POST"])
def edit_question(question_id):
    question = Question.query.get_or_404(question_id)
    subjects = Subject.query.order_by(Subject.name).all()
    if request.method == "POST":
        subject_id = request.form.get("subject_id", type=int)
        content = request.form.get("content", "").strip()
        difficulty = request.form.get("difficulty", "medium")
        choices_content = request.form.getlist("choice_content")
        correct_index = request.form.get("correct_choice", type=int)

        if not subject_id or not content:
            flash("Vui lòng điền đầy đủ thông tin câu hỏi.", "danger")
            return render_template("question_form.html", question=question, subjects=subjects)

        valid_choices = [c.strip() for c in choices_content if c.strip()]
        if len(valid_choices) < 2:
            flash("Câu hỏi cần ít nhất 2 đáp án.", "danger")
            return render_template("question_form.html", question=question, subjects=subjects)

        if correct_index is None or correct_index >= len(valid_choices):
            flash("Vui lòng chọn đáp án đúng.", "danger")
            return render_template("question_form.html", question=question, subjects=subjects)

        question.subject_id = subject_id
        question.content = content
        question.difficulty = difficulty

        for choice in question.choices:
            db.session.delete(choice)
        db.session.flush()

        for i, choice_content in enumerate(valid_choices):
            choice = Choice(
                question_id=question.id,
                content=choice_content,
                is_correct=(i == correct_index),
            )
            db.session.add(choice)

        db.session.commit()
        flash("Đã cập nhật câu hỏi.", "success")
        return redirect(url_for("questions"))

    return render_template("question_form.html", question=question, subjects=subjects)


@app.route("/questions/<int:question_id>/delete", methods=["POST"])
def delete_question(question_id):
    question = Question.query.get_or_404(question_id)
    db.session.delete(question)
    db.session.commit()
    flash("Đã xóa câu hỏi.", "success")
    return redirect(url_for("questions"))


# ─── Exams ───────────────────────────────────────────────────────────────────

@app.route("/exams")
def exams():
    exams = Exam.query.order_by(Exam.created_at.desc()).all()
    return render_template("exams.html", exams=exams)


@app.route("/exams/add", methods=["GET", "POST"])
def add_exam():
    subjects = Subject.query.order_by(Subject.name).all()
    questions = Question.query.order_by(Question.subject_id, Question.id).all()
    if request.method == "POST":
        title = request.form.get("title", "").strip()
        description = request.form.get("description", "").strip()
        duration = request.form.get("duration_minutes", 60, type=int)
        question_ids = request.form.getlist("question_ids", type=int)

        if not title:
            flash("Tên đề thi không được để trống.", "danger")
            return render_template("exam_form.html", exam=None, subjects=subjects, questions=questions)

        if len(question_ids) == 0:
            flash("Đề thi cần ít nhất 1 câu hỏi.", "danger")
            return render_template("exam_form.html", exam=None, subjects=subjects, questions=questions)

        exam = Exam(title=title, description=description, duration_minutes=duration)
        db.session.add(exam)
        db.session.flush()

        for order, qid in enumerate(question_ids):
            eq = ExamQuestion(exam_id=exam.id, question_id=qid, order_num=order)
            db.session.add(eq)

        db.session.commit()
        flash(f'Đã tạo đề thi "{title}".', "success")
        return redirect(url_for("exams"))

    return render_template("exam_form.html", exam=None, subjects=subjects, questions=questions)


@app.route("/exams/<int:exam_id>/edit", methods=["GET", "POST"])
def edit_exam(exam_id):
    exam = Exam.query.get_or_404(exam_id)
    subjects = Subject.query.order_by(Subject.name).all()
    questions = Question.query.order_by(Question.subject_id, Question.id).all()
    if request.method == "POST":
        title = request.form.get("title", "").strip()
        description = request.form.get("description", "").strip()
        duration = request.form.get("duration_minutes", 60, type=int)
        question_ids = request.form.getlist("question_ids", type=int)

        if not title:
            flash("Tên đề thi không được để trống.", "danger")
            return render_template("exam_form.html", exam=exam, subjects=subjects, questions=questions)

        if len(question_ids) == 0:
            flash("Đề thi cần ít nhất 1 câu hỏi.", "danger")
            return render_template("exam_form.html", exam=exam, subjects=subjects, questions=questions)

        exam.title = title
        exam.description = description
        exam.duration_minutes = duration

        for eq in exam.exam_questions:
            db.session.delete(eq)
        db.session.flush()

        for order, qid in enumerate(question_ids):
            eq = ExamQuestion(exam_id=exam.id, question_id=qid, order_num=order)
            db.session.add(eq)

        db.session.commit()
        flash(f'Đã cập nhật đề thi "{title}".', "success")
        return redirect(url_for("exams"))

    return render_template("exam_form.html", exam=exam, subjects=subjects, questions=questions)


@app.route("/exams/<int:exam_id>/delete", methods=["POST"])
def delete_exam(exam_id):
    exam = Exam.query.get_or_404(exam_id)
    title = exam.title
    db.session.delete(exam)
    db.session.commit()
    flash(f'Đã xóa đề thi "{title}".', "success")
    return redirect(url_for("exams"))


@app.route("/exams/<int:exam_id>/take")
def take_exam(exam_id):
    exam = Exam.query.get_or_404(exam_id)
    exam_questions = (
        ExamQuestion.query.filter_by(exam_id=exam_id)
        .order_by(ExamQuestion.order_num)
        .all()
    )
    questions = [eq.question for eq in exam_questions]
    return render_template("take_exam.html", exam=exam, questions=questions)


@app.route("/exams/<int:exam_id>/submit", methods=["POST"])
def submit_exam(exam_id):
    exam = Exam.query.get_or_404(exam_id)
    exam_questions = (
        ExamQuestion.query.filter_by(exam_id=exam_id)
        .order_by(ExamQuestion.order_num)
        .all()
    )
    questions = [eq.question for eq in exam_questions]

    score = 0
    results = []
    for question in questions:
        selected_choice_id = request.form.get(f"question_{question.id}", type=int)
        correct_choice = next((c for c in question.choices if c.is_correct), None)
        selected_choice = next((c for c in question.choices if c.id == selected_choice_id), None)
        is_correct = selected_choice is not None and selected_choice.is_correct
        if is_correct:
            score += 1
        results.append({
            "question": question,
            "selected": selected_choice,
            "correct": correct_choice,
            "is_correct": is_correct,
        })

    total = len(questions)
    percentage = round(score / total * 100) if total > 0 else 0

    return render_template(
        "exam_result.html",
        exam=exam,
        results=results,
        score=score,
        total=total,
        percentage=percentage,
    )


# ─── API ─────────────────────────────────────────────────────────────────────

@app.route("/api/questions")
def api_questions():
    subject_id = request.args.get("subject_id", type=int)
    query = Question.query
    if subject_id:
        query = query.filter_by(subject_id=subject_id)
    questions = query.all()
    return jsonify([q.to_dict() for q in questions])


if __name__ == "__main__":
    debug = os.environ.get("FLASK_DEBUG", "0") == "1"
    app.run(debug=debug)
