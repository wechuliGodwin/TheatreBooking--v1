<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurgeryController;

// Route::get('/', function () {
//     return view('layouts/index');
// });
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/theatre/requested_surgeries', [SurgeryController::class, 'index'])->name('requested_surgeries');
