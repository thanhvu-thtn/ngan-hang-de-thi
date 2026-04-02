<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CognitiveLevelController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EssayController;
use App\Http\Controllers\ObjectiveController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionLayoutController;
use App\Http\Controllers\QuestionTypeController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicAssignmentController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicTypeController;
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
        // Bước 1: Setup
        Route::post('/questions/setup', [QuestionController::class, 'storeSetup'])->name('questions.storeSetup');

        // Bước 2: Essay (Tự luận)
        Route::get('/questions/essay/create', [EssayController::class, 'create'])->name('questions.es.create');
        Route::post('/questions/essay', [EssayController::class, 'store'])->name('questions.es.store');
        // Resource chính
        Route::resource('questions', QuestionController::class)->only(['index', 'create']);
    });
});
