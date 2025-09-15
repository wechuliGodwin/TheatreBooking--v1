<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\requestedSurgeries;
use App\Http\Controllers\requestedSurgeriesDetails;
use App\Http\Controllers\TheatreController;

// Route::get('/', function () {
//     return view('layouts/index');
// });
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/requested_surgeries', [DashboardController::class, 'requestedSurgeries'])->name(name: 'requested_surgeries.index');
Route::get('/requested_surgeries/details/{id}', [DashboardController::class, 'requestedSurgeriesDetails'])->name('requested_surgery_details.show');
