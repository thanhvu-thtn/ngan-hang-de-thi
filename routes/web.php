<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CognitiveLevelController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EssayController;
use App\Http\Controllers\MultipleChoiceController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionLayoutController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\SharedContextController;
use App\Http\Controllers\ShortAnswerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicAssignmentController;
use App\Http\Controllers\TopicController; // Đã thêm
use App\Http\Controllers\TopicTypeController;
use App\Http\Controllers\TrueFalseController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController; // Đã thêm
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// 1. TRANG CHỦ & LOGIN
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. NHÓM ĐÃ ĐĂNG NHẬP
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/api/upload-editor-image', [UploadController::class, 'uploadImage'])->name('upload.editor.image');

    // --- ADMIN ---
    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('subjects', SubjectController::class);
        Route::resource('topic-types', TopicTypeController::class);
        Route::resource('question-types', QuestionTypeController::class)->except(['show']);
        Route::resource('cognitive-levels', CognitiveLevelController::class)->except(['show']);
        Route::resource('question-layouts', QuestionLayoutController::class)->parameters(['question-layouts' => 'question_layout']);
        Route::resource('permissions', PermissionController::class)->except(['show', 'create']);
    });

    // --- TỔ TRƯỞNG & ADMIN ---
    Route::middleware('role:Admin|Tổ trưởng')->group(function () {
        Route::get('/topics/export', [TopicController::class, 'exportWord'])->name('topics.export');
        Route::resource('topics', TopicController::class);
        Route::resource('contents', ContentController::class);
        Route::resource('objectives', ObjectiveController::class);

        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/assignments', [AssignmentController::class, 'update'])->name('assignments.update');

        Route::get('/topic-assignments', [TopicAssignmentController::class, 'index'])->name('topic-assignments.index');
        Route::post('/topic-assignments', [TopicAssignmentController::class, 'update'])->name('topic-assignments.update');
    });

    // --- NGÂN HÀNG CÂU HỎI ---
    Route::middleware(['role_or_permission:Admin|Tổ trưởng|bien-soan-cau-hoi'])->group(function () {

        // =========================================================================
        // 1. NHÓM UPLOAD (YÊU CẦU PHẢI CÓ THÊM QUYỀN UPLOAD-CAU-HOI)
        // =========================================================================
        Route::middleware(['role_or_permission:upload-cau-hoi'])
            ->prefix('questions/upload')
            ->group(function () {
                // Giữ nguyên name() rỗng cho route gốc để khớp tên 'questions.upload' của bạn
                Route::get('/', [QuestionController::class, 'showUploadForm'])->name('questions.upload');
                Route::post('/preview', [QuestionController::class, 'previewUpload'])->name('questions.upload.preview');
                Route::post('/import', [QuestionController::class, 'importData'])->name('questions.upload.import');
            });

        // =========================================================================
        // 2. NHÓM CÁC TÍNH NĂNG QUESTION (Sử dụng chung prefix 'questions' và name 'questions.')
        // =========================================================================
        Route::prefix('questions')->name('questions.')->group(function () {

            // Bước 1: Setup
            Route::post('setup', [QuestionController::class, 'storeSetup'])->name('storeSetup');

            // Bước 2: Essay (Tự luận)
            Route::prefix('essay')->name('es.')->group(function () {
                Route::get('create', [EssayController::class, 'create'])->name('create');
                Route::post('/', [EssayController::class, 'store'])->name('store');

                // Các route liên quan đến in ấn (Đặt trước các route có chứa {id} để không bị trùng lặp pattern)
                Route::get('{id}/printpdf', [EssayController::class, 'printPdf'])->name('printpdf');
                Route::get('{id}/print-preview', [EssayController::class, 'printPdf'])->name('printPdf');
                Route::get('{id}/print-word', [EssayController::class, 'printWord'])->name('printWord');

                Route::get('{id}/show', [EssayController::class, 'show'])->name('show');
                Route::get('{id}/edit', [EssayController::class, 'edit'])->name('edit');
                Route::put('{id}/update', [EssayController::class, 'update'])->name('update');
            });

            // Multiple Choice (Trắc nghiệm nhiều lựa chọn)
            Route::prefix('multiple-choice')->name('mc.')->group(function () {
                Route::get('create', [MultipleChoiceController::class, 'create'])->name('create');
                Route::post('/', [MultipleChoiceController::class, 'store'])->name('store');
                Route::get('{id}/show', [MultipleChoiceController::class, 'show'])->name('show');
                Route::get('{id}/edit', [MultipleChoiceController::class, 'edit'])->name('edit');
            });

            // Short Answer (Trắc nghiệm trả lời ngắn)
            Route::prefix('short-answer')->name('sa.')->group(function () {
                Route::get('create', [ShortAnswerController::class, 'create'])->name('create');
                Route::post('/', [ShortAnswerController::class, 'store'])->name('store');
                Route::get('{id}/show', [ShortAnswerController::class, 'show'])->name('show');
                Route::get('{id}/edit', [ShortAnswerController::class, 'edit'])->name('edit');
            });

            // True False (Trắc nghiệm đúng sai)
            Route::prefix('true-false')->name('tf.')->group(function () {
                Route::get('create', [TrueFalseController::class, 'create'])->name('create');
                Route::post('/', [TrueFalseController::class, 'store'])->name('store');
                Route::get('{id}/show', [TrueFalseController::class, 'show'])->name('show');
                Route::get('{id}/edit', [TrueFalseController::class, 'edit'])->name('edit');
            });
        });

        // =========================================================================
        // 3. NHÓM SHARED CONTEXT (Đoạn thông tin dùng chung)
        // =========================================================================
        Route::prefix('shared-contexts')->name('shared_contexts.')->group(function () {
            Route::post('create', [SharedContextController::class, 'store'])->name('store');
            Route::get('{id}/show', [SharedContextController::class, 'show'])->name('show');
            Route::get('{id}/edit', [SharedContextController::class, 'edit'])->name('edit');
            Route::put('{id}', [SharedContextController::class, 'update'])->name('update');
        });

        // =========================================================================
        // 4. RESOURCE CHÍNH QUẢN LÝ CÂU HỎI
        // =========================================================================
        Route::resource('questions', QuestionController::class)
            ->only(['index', 'create', 'show', 'edit', 'update', 'destroy'])
            ->parameters(['questions' => 'question']);
    });
});
