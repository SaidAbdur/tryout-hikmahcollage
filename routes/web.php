<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

/* ---------------- Public: registration & student login ---------------- */
Route::middleware('guest:student')->group(function () {
    Route::get('/register', [StudentAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [StudentAuthController::class, 'register'])->middleware('throttle:10,1');
    Route::get('/login', [StudentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentAuthController::class, 'login'])->middleware('throttle:10,1');
});
Route::get('/verify/{student}', [StudentAuthController::class, 'verify'])->middleware('signed')->name('student.verify');
Route::post('/logout', [StudentAuthController::class, 'logout'])->name('logout');

/* ---------------- Student area ---------------- */
Route::middleware('auth:student')->group(function () {
    Route::get('/dashboard', [Student\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [Student\DashboardController::class, 'history'])->name('history');

    Route::post('/tryout/{subject}/start', [Student\TryoutController::class, 'start'])->name('tryout.start');
    Route::get('/tryout/{session}', [Student\TryoutController::class, 'show'])->name('tryout.show');
    Route::post('/tryout/{session}/answer', [Student\TryoutController::class, 'answer'])->name('tryout.answer');
    Route::post('/tryout/{session}/submit', [Student\TryoutController::class, 'submit'])->name('tryout.submit');
    Route::get('/tryout/{session}/result', [Student\TryoutController::class, 'result'])->name('tryout.result');
});

/* ---------------- Admin area ---------------- */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login'])->middleware('throttle:6,1');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('analytics/registrations', [Admin\DashboardController::class, 'registrations'])->name('analytics.registrations');

        Route::get('students/export', [Admin\StudentController::class, 'export'])->name('students.export'); // before {student}
        Route::resource('students', Admin\StudentController::class)->except(['create', 'store']);

        Route::get('monitoring', [Admin\MonitoringController::class, 'index'])->name('monitoring');
        Route::get('monitoring/data', [Admin\MonitoringController::class, 'data'])->name('monitoring.data');
    });
});
