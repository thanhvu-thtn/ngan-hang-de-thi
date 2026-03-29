<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicTypeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ObjectiveController;

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
        Route::resource('contents', App\Http\Controllers\ContentController::class);
        Route::resource('objectives', ObjectiveController::class);
        
        // Quản lý Phân quyền Giáo viên (Dành cho Tổ trưởng)
        Route::get('/assignments', [AssignmentController::class, 'index'])->name('assignments.index');
        Route::post('/assignments', [AssignmentController::class, 'update'])->name('assignments.update');
    });
    });

});