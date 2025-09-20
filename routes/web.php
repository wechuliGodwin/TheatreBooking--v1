<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurgeryController;

// The more specific route must come first.
Route::get('/requested_surgeries/details/{id}', [DashboardController::class, 'surgeryDetails'])
    ->where('id', '.*')
    ->name('surgery_details.show');

Route::get('/requested_surgeries/{status?}', [DashboardController::class, 'surgeries'])->name('requested_surgeries.index');

Route::get('/surgery/book/{sessionNumber}', [SurgeryController::class, 'showBookingForm'])->where('sessionNumber', '.*')->name('surgery.book');

Route::post('/surgery/store', [SurgeryController::class, 'store'])->name('booked_theatre.store');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
