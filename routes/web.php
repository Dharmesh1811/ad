<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdmitCardController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\IdCardController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/auth/register-password', [AuthController::class, 'registerPassword'])->name('auth.register-password');
Route::post('/auth/login-password', [AuthController::class, 'loginPassword'])->name('auth.login-password');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/forgot-password/otp', [AuthController::class, 'showOtpForm'])->name('password.otp.form');
Route::post('/forgot-password/otp', [AuthController::class, 'verifyOtp'])->name('password.otp.verify');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('password.reset.form');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/track-status', [StatusController::class, 'index'])->name('status.index');
Route::post('/check-status', [StatusController::class, 'show'])->name('status.show');
Route::get('/vacancies', [VacancyController::class, 'index'])->name('vacancies.index');
Route::get('/admit-card', [AdmitCardController::class, 'index'])->name('admit.index');
Route::post('/admit-card', [AdmitCardController::class, 'show'])->name('admit.show');
Route::get('/admit-card/pdf/{application}', [AdmitCardController::class, 'download'])->name('id-card.download-pdf');



// Public ID Card Download Routes
Route::get('/download-id-card', [IdCardController::class, 'form'])->name('id-card.form');
Route::post('/download-id-card', [IdCardController::class, 'download'])->name('id-card.download');
Route::get('/captcha/{config?}', [\Mews\Captcha\CaptchaController::class, 'getCaptcha']);
Route::get('/uploads/{path}', [ApplicationController::class, 'showUpload'])
    ->where('path', '.*')
    ->name('uploads.show');


Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/apply-online/{exam}', [ApplicationController::class, 'create'])->name('application.create');
    Route::post('/apply-online', [ApplicationController::class, 'store'])->name('application.store');
    Route::get('/application/pdf/{application}', [ApplicationController::class, 'downloadPdf'])->name('application.pdf');

    // ---------------------------------------------------------------------------
    // Razorpay Payment Routes
    // ---------------------------------------------------------------------------
    // Step 1: Show payment page (creates Razorpay Order server-side)
    Route::get('/payments/{application}', [PaymentController::class, 'create'])->name('payments.create');

    // Step 2: AJAX endpoint — verify Razorpay signature & save payment
    Route::post('/payments/verify', [PaymentController::class, 'store'])->name('payments.store');

    // Step 3: Post-payment result pages
    Route::get('/payments/{application}/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/{application}/failed', [PaymentController::class, 'failed'])->name('payments.failed');
});


Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::patch('/applications/{application}', [AdminController::class, 'update'])->name('applications.update');
    Route::get('/applications/{application}/pdf', [AdminController::class, 'downloadApplicationPdf'])->name('applications.pdf');
    Route::post('/exams', [AdminController::class, 'storeExam'])->name('exams.store');
    Route::patch('/exams/{exam}', [AdminController::class, 'updateExam'])->name('exams.update');
    Route::post('/exams/{exam}/fields', [AdminController::class, 'storeField'])->name('fields.store');
    Route::patch('/exams/{exam}/fields/{field}', [AdminController::class, 'updateField'])->name('fields.update');
    Route::delete('/exams/{exam}/fields/{field}', [AdminController::class, 'destroyField'])->name('fields.destroy');
    Route::patch('/exams/{exam}/toggle', [AdminController::class, 'toggleExam'])->name('exams.toggle');
    Route::get('/exams/{exam}/builder', [AdminController::class, 'builder'])->name('exams.builder');
    Route::delete('/exams/{exam}', [AdminController::class, 'destroyExam'])->name('exams.destroy');
    Route::get('/export', [AdminController::class, 'export'])->name('export');
    Route::post('/syllabus', [AdminController::class, 'storeSyllabus'])->name('syllabus.store');
    Route::delete('/syllabus/{syllabus}', [AdminController::class, 'destroySyllabus'])->name('syllabus.destroy');
});
