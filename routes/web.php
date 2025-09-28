<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    // The more specific route must come first.
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
    Route::delete('/surgeries/{id}', [SurgeryController::class, 'destroy'])->name('surgeries.destroy');
    Route::post('/surgery/reschedule/{id}', [SurgeryController::class, 'reschedule'])->name('surgery.reschedule');
    Route::get('/surgeries/rescheduled', [SurgeryController::class, 'rescheduledAppointments'])->name('surgeries.rescheduled');
    Route::get('/surgeries/rescheduled/export-csv', [SurgeryController::class, 'exportRescheduledAppointmentsCsv'])->name('surgeries.rescheduled.export_csv');
    Route::get('/surgeries/cancelled/export-csv', [SurgeryController::class, 'exportCancelledSurgeriesCsv'])->name('surgeries.cancelled.export_csv');
    Route::get('/surgeries/finalized', [DashboardController::class, 'finalizedSurgeries'])->name('surgeries.finalized');
    
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Email verification
    Route::get('email/verify', [VerificationController::class,'notice'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerificationController::class,'verify'])->middleware(['signed'])->name('verification.verify');
    Route::post('email/resend', [VerificationController::class,'resend'])->name('verification.resend');

    // User management (protected by role middleware)
    Route::resource('users', UserController::class)->names('users')->middleware(['verified','role:super admin|admin']);
});

// Auth Routes (login, register, password reset) remain outside the auth group
Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
Route::post('login', [LoginController::class,'login'])->name('login.post');
Route::post('logout', [LoginController::class,'logout'])->name('logout');

Route::get('register', [RegisterController::class,'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class,'register'])->name('register.post');

// Password Reset
Route::get('password/reset', [ForgotPasswordController::class,'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');
