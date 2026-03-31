<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CognitiveLevelController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionLayoutController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicTypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermissionController;

// 1. TRANG CHỦ & LOGIN
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// 2. NHÓM YÊU CẦU ĐĂNG NHẬP (Chung cho tất cả mọi người đã login)
Route::middleware('auth')->group(function () {

    // Trang chào mừng sau login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Đăng xuất
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- NHÓM QUẢN TRỊ VIÊN (Chỉ Admin mới được vào) ---
    Route::middleware('role:Admin')->group(function () {
        // Quản lý Người dùng
        Route::resource('users', UserController::class);

        // Quản lý Môn học
        Route::resource('subjects', SubjectController::class);

        // Quản lý Loại chuyên đề
        Route::resource('topic-types', TopicTypeController::class);
        // Quản lý Loại câu hỏi
        Route::resource('question-types', QuestionTypeController::class);
        // Quản lý Mức độ nhận thức (MỚI THÊM)
        Route::resource('cognitive-levels', CognitiveLevelController::class);
        Route::resource('question-types', QuestionTypeController::class)->except(['show']);
        Route::resource('cognitive-levels', CognitiveLevelController::class)->except(['show']);
        // Quản lý Bố cục câu hỏi (MỚI THÊM)
        Route::resource('question-layouts', QuestionLayoutController::class)->parameters([
            'question-layouts' => 'question_layout',
        ]);
        // Admin toàn quyền quản lý Permissions
        Route::resource('permissions', PermissionController::class)->except(['show', 'create']);
    });

    // --- NHÓM TỔ TRƯỞNG & ADMIN (Mở rộng cho mục tiêu của bạn) ---
    Route::middleware('role:Admin|Tổ trưởng')->group(function () {
        // Quản lý Chuyên đề
        Route::resource('topics', TopicController::class);
        // Quản lý Phân quyền Giáo viên (Dành cho Tổ trưởng)
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/assignments', [AssignmentController::class, 'update'])->name('assignments.update');
        Route::middleware('role:Admin|Tổ trưởng')->group(function () {
            // Quản lý Chuyên đề
            Route::resource('topics', TopicController::class);

            // Quản lý Nội dung chuyên đề (MỚI THÊM)
            Route::resource('contents', ContentController::class);
            Route::resource('objectives', ObjectiveController::class);

            // Quản lý Phân quyền Giáo viên (Dành cho Tổ trưởng)
            Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
            Route::post('/assignments', [AssignmentController::class, 'update'])->name('assignments.update');
        });
    });

});

// Group dành cho Quản lý Ngân hàng câu hỏi
Route::middleware(['auth', 'role_or_permission:admin|to-truong|bien-soan-cau-hoi'])->group(function () {
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    
    // Sau này bạn có thể ném các route liên quan vào đây, ví dụ:
    // Route::get('/questions/create', ...);
    // Route::post('/questions', ...);
});
