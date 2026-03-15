<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});


Route::middleware('guest')->group(function () {

    // 1. مسار التحقق من الهوية (الخطوة الأولى في المودال)
    Route::post('password/verify-identity', [ForgotPasswordController::class, 'verifyIdentity'])
        ->name('password.verify-identity');

    // 2. مسار إتمام تغيير كلمة المرور والدخول التلقائي (الخطوة الثانية في المودال)
    Route::post('password/reset', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.reset');
});