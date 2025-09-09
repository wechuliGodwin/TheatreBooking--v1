<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('layouts/index');
// });
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
