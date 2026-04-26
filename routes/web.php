<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdmitCardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/auth/request-otp', [AuthController::class, 'requestOtp'])->name('auth.request-otp');
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp'])->name('auth.verify-otp');
Route::post('/auth/register-password', [AuthController::class, 'registerPassword'])->name('auth.register-password');
Route::post('/auth/login-password', [AuthController::class, 'loginPassword'])->name('auth.login-password');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/track-status', [StatusController::class, 'index'])->name('status.index');
Route::post('/check-status', [StatusController::class, 'show'])->name('status.show');
Route::get('/admit-card', [AdmitCardController::class, 'index'])->name('admit.index');
Route::post('/admit-card', [AdmitCardController::class, 'show'])->name('admit.show');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/apply-online/{exam}', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/apply-online', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/payments/{application}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::patch('/applications/{application}', [AdminController::class, 'update'])->name('applications.update');
    Route::post('/exams', [AdminController::class, 'storeExam'])->name('exams.store');
    Route::patch('/exams/{exam}', [AdminController::class, 'updateExam'])->name('exams.update');
    Route::post('/exams/{exam}/fields', [AdminController::class, 'storeField'])->name('fields.store');
    Route::patch('/exams/{exam}/fields/{field}', [AdminController::class, 'updateField'])->name('fields.update');
    Route::delete('/exams/{exam}/fields/{field}', [AdminController::class, 'destroyField'])->name('fields.destroy');
    Route::patch('/exams/{exam}/toggle', [AdminController::class, 'toggleExam'])->name('exams.toggle');
    Route::delete('/exams/{exam}', [AdminController::class, 'destroyExam'])->name('exams.destroy');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
});
