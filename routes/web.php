<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TopicTypeController;

// Thêm dòng này vào nhóm route đã đăng nhập (nếu bạn có auth) hoặc cứ để tạm ở ngoài để test:
Route::resource('subjects', SubjectController::class);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

//Nhóm các route xử lý Subjects
Route::prefix('subjects')->group(function () {
    Route::get('/', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/create', [SubjectController::class, 'create'])->name('subjects.create');
    Route::post('/', [SubjectController::class, 'store'])->name('subjects.store');
    Route::get('/{subject}', [SubjectController::class, 'show'])->name('subjects.show');
    Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('subjects.edit');
    Route::put('/{subject}', [SubjectController::class, 'update'])->name('subjects.update');
    Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('subjects.destroy');
});

//Nhóm các route xử lý Topic_types
Route::prefix('topic_types')->group(function () {
    Route::get('/', [TopicTypeController::class, 'index'])->name('topic-types.index');
    Route::get('/create', [TopicTypeController::class, 'create'])->name('topic-types.create');
    Route::post('/', [TopicTypeController::class, 'store'])->name('topic-types.store');
    Route::get('/{topic_type}', [TopicTypeController::class, 'show'])->name('topic-types.show');
    Route::get('/{topic_type}/edit', [TopicTypeController::class, 'edit'])->name('topic-types.edit');
    Route::put('/{topic_type}', [TopicTypeController::class, 'update'])->name('topic-types.update');
    Route::delete('/{topic_type}', [TopicTypeController::class, 'destroy'])->name('topic-types.destroy');
});
