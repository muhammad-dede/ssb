<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return 'Home';
})->name('home');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'has_student']);

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/student.php';
