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
use App\Http\Controllers\ShortAnswerController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicAssignmentController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TopicTypeController; // Đã thêm
use App\Http\Controllers\TrueFalseController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SharedContextController; // Đã thêm
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
        //Route::post('/questions/update', [QuestionController::class, 'storeSetup'])->name('questions.update');

        // Bước 2: Essay (Tự luận)
        Route::get('/questions/essay/create', [EssayController::class, 'create'])->name('questions.es.create');
        Route::post('/questions/essay', [EssayController::class, 'store'])->name('questions.es.store');
        Route::get('/questions/essay/{id}/show', [EssayController::class, 'show'])->name('questions.es.show');
        Route::get('/questions/essay/{id}/edit', [EssayController::class, 'edit'])->name('questions.es.edit');
        Route::put('/questions/essay/{id}/update', [EssayController::class, 'update'])->name('questions.es.update');

        // Route cho tính năng in (NÊN ĐẶT TRƯỚC CÁC ROUTE SHOW/EDIT NẾU CÙNG PREFIX)
        Route::get('/questions/essay/{id}/print-preview', [EssayController::class, 'printPdf'])->name('questions.es.printPdf');
        Route::get('/questions/essay/{id}/print-word', [EssayController::class, 'printWord'])->name('questions.es.printWord'); 
        // Resource của MultipleChoiceController
        Route::get('/questions/multiple-choice/create', [MultipleChoiceController::class, 'create'])->name('questions.mc.create');
        Route::post('/questions/multiple-choice', [MultipleChoiceController::class, 'store'])->name('questions.mc.store');
        Route::get('/questions/multiple-choice/{id}/show', [MultipleChoiceController::class, 'show'])->name('questions.mc.show');
        Route::get('/questions/multiple-choice/{id}/edit', [MultipleChoiceController::class, 'edit'])->name('questions.mc.edit');
        // Resource của ShortAnswerController
        Route::get('/questions/short-answer/create', [ShortAnswerController::class, 'create'])->name('questions.sa.create');
        Route::post('/questions/short-answer', [ShortAnswerController::class, 'store'])->name('questions.sa.store');
        Route::get('/questions/short-answer/{id}/show', [ShortAnswerController::class, 'show'])->name('questions.sa.show');
        Route::get('/questions/short-answer/{id}/edit', [ShortAnswerController::class, 'edit'])->name('questions.sa.edit');
        // Resource của TrueFalseController (nếu có)
        Route::get('/questions/true-false/create', [TrueFalseController::class, 'create'])->name('questions.tf.create');
        Route::post('/questions/true-false', [TrueFalseController::class, 'store'])->name('questions.tf.store');
        Route::get('/questions/true-false/{id}/show', [TrueFalseController::class, 'show'])->name('questions.tf.show');
        Route::get('/questions/true-false/{id}/edit', [TrueFalseController::class, 'edit'])->name('questions.tf.edit');
        // Route của SharedContextController
         Route::get('/shared-contexts/{id}/show', [SharedContextController::class, 'show'])->name('shared_contexts.show');
         Route::get('/shared-contexts/{id}/edit', [SharedContextController::class, 'edit'])->name('shared_contexts.edit');
         Route::post('/shared-contexts/create', [SharedContextController::class, 'store'])->name('shared_contexts.store');
         Route::put('/shared-contexts/{id}', [SharedContextController::class, 'update'])->name('shared_contexts.update');
        // Resource chính
        Route::resource('questions', QuestionController::class)->only(['index', 'create', 'show','edit','update'])->parameters(['questions' => 'question']);
    });
});
