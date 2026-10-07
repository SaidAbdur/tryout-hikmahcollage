<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\PerformanceController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\TryoutController;

Route::get('/', function () {
    return redirect()->route(auth('student')->check() ? 'dashboard' : 'login');
})->name('home');

Route::middleware('guest:student')->group(function () {
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->name('register.store');
    Route::get('/registration/proofs', [StudentAuthController::class, 'showProofUpload'])->name('registration.proofs');
    Route::post('/registration/proofs', [StudentAuthController::class, 'uploadProofs'])->name('registration.proofs.store');
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth:student')->group(function () {
    Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');
    Route::get('/waiting', [StudentAuthController::class, 'showWaiting'])->name('registration.waiting');
});

Route::middleware(['auth:student', 'student.status'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [StudentDashboardController::class, 'history'])->name('history');
    Route::post('/tryout-series/{tryoutSeries}/subjects/{subject}/tryout', [TryoutController::class, 'start'])->name('tryout.start');
    Route::get('/tryouts/{session}', [TryoutController::class, 'show'])->name('tryout.show');
    Route::post('/tryouts/{session}/answer', [TryoutController::class, 'answer'])->name('tryout.answer');
    Route::post('/tryouts/{session}/submit', [TryoutController::class, 'submit'])->name('tryout.submit');
    Route::get('/tryouts/{session}/result', [TryoutController::class, 'result'])->name('tryout.result');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/students/export', [PerformanceController::class, 'export'])->name('students.export');
        Route::get('/students/active', [PerformanceController::class, 'index'])->name('students.active');
        Route::get('/students/active/{student}', [PerformanceController::class, 'show'])->name('students.performance');
        Route::get('/students/pending', [ApprovalController::class, 'index'])->name('students.pending');
        Route::post('/students/{student}/status', [ApprovalController::class, 'updateStatus'])->name('students.status');
        Route::get('/students/{student}/proofs/{proof}', [ApprovalController::class, 'proof'])->name('students.proofs');
        Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
        Route::get('/questions/series/create', [QuestionController::class, 'createSeries'])->name('questions.series.create');
        Route::post('/questions/series', [QuestionController::class, 'storeSeries'])->name('questions.series.store');
        Route::get('/questions/{tryoutSeries}', [QuestionController::class, 'showSeries'])->name('questions.series');
        Route::get('/questions/{tryoutSeries}/subjects/{subject}', [QuestionController::class, 'showSubject'])->name('questions.subject');
        Route::get('/questions/{tryoutSeries}/subjects/{subject}/create', [QuestionController::class, 'create'])->name('questions.create');
        Route::post('/questions/{tryoutSeries}/subjects/{subject}', [QuestionController::class, 'store'])->name('questions.store');
        Route::get('/questions/{tryoutSeries}/subjects/{subject}/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
        Route::put('/questions/{tryoutSeries}/subjects/{subject}/{question}', [QuestionController::class, 'update'])->name('questions.update');
        Route::delete('/questions/{tryoutSeries}/subjects/{subject}/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
    });
});
