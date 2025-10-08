<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurgeryController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;


// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // The more specific route must come first.
    Route::middleware(['role:surgeon'])->group(function () {

        Route::delete('/surgeries/{id}', [SurgeryController::class, 'destroy'])->name('surgeries.destroy');
        Route::post('/surgery/reschedule/{id}', [SurgeryController::class, 'reschedule'])->name('surgery.reschedule');
        Route::get('/surgeries/rescheduled', [SurgeryController::class, 'rescheduledAppointments'])->name('surgeries.rescheduled');

        Route::get('/surgeries/finalized', [DashboardController::class, 'finalizedSurgeries'])->name('surgeries.finalized');
    });

    Route::middleware(['role:surgeon|nurse'])->group(function () {
        Route::get('/patient-info/{patientNumber}', [App\Http\Controllers\SurgeryController::class, 'getPatientInfo'])->name('patient.info');

        Route::get('/requested_surgeries/details/{id}', [DashboardController::class, 'surgeryDetails'])
            ->where('id', '.*')
            ->name('surgery_details.show');

        Route::get('/requested_surgeries', [DashboardController::class, 'surgeries'])->name('requested_surgeries.index');
        Route::get('/surgeries/filter', [SurgeryController::class, 'filterByStatus'])->name('surgeries.filter');
        Route::get('/surgeries/cancelled', [SurgeryController::class, 'cancelled'])->name('surgeries.cancelled');
        Route::post('/surgery/cancel', [SurgeryController::class, 'cancel'])->name('surgery.cancel');
        Route::get('/surgery/create', [SurgeryController::class, 'showBookingForm'])->name('surgery.create');
        Route::get('/surgery/book/{sessionNumber}', [SurgeryController::class, 'showBookingForm'])->where('sessionNumber', '.*')->name('surgery.book');
        Route::get('/surgery/edit/{id}', [SurgeryController::class, 'edit'])->name('surgery.edit');
        Route::put('/surgery/update/{id}', [SurgeryController::class, 'update'])->name('surgery.update');
        Route::post('/surgery/store', [SurgeryController::class, 'store'])->name('booked_theatre.store');
        Route::get('/surgeries/rescheduled/export-csv', [SurgeryController::class, 'exportRescheduledAppointmentsCsv'])->name('surgeries.rescheduled.export_csv');
        Route::get('/surgeries/cancelled/export-csv', [SurgeryController::class, 'exportCancelledSurgeriesCsv'])->name('surgeries.cancelled.export_csv');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });
});


// Auth Routes (login, register, password reset) remain outside the auth group
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [AuthController::class, 'register'])->name('register.post');

// Password Reset
Route::get('password/reset', [AuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'reset'])->name('password.update');

// 2FA Routes
Route::get('2fa', [AuthController::class, 'show2FAForm'])->name('2fa');
Route::post('2fa', [AuthController::class, 'verify2FA'])->name('2fa.verify');

// Email Verification Routes
Route::middleware('auth')->group(function () {
    Route::get('verification/notice', [AuthController::class, 'notice'])->name('verification.notice');
    Route::get('verification/verify', [AuthController::class, 'verify'])->name('verification.verify');
    Route::post('verification/resend', [AuthController::class, 'resend'])->name('verification.resend');
});


// // User Management (Admin only)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('users/', [AuthController::class, 'index'])->name('users.index');
    Route::get('/create', [AuthController::class, 'create'])->name('users.create');
    Route::post('/', [AuthController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [AuthController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [AuthController::class, 'update'])->name('users.update');
    Route::patch('/{user}/password', [AuthController::class, 'updatePassword'])->name('users.update-password');
    Route::patch('/{user}/toggle-status', [AuthController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::delete('/{user}', [AuthController::class, 'destroy'])->name('users.destroy');
});
