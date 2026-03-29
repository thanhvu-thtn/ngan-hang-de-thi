<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicTypeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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
        
        // Đây sẽ là nơi chúng ta thêm route Phân quyền Giáo viên ở bước sau
        // Route::get('/assignment', [AssignmentController::class, 'index'])->name('assignment.index');
    });

});