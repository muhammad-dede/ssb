<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return 'Home';
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Role
    Route::resource('role', App\Http\Controllers\RoleController::class)->except(['show']);
    // User
    Route::post('user/{user}/status', [App\Http\Controllers\UserController::class, 'status'])->name('user.status');
    Route::resource('user', App\Http\Controllers\UserController::class)->except(['show']);
    // Bank Account
    Route::post('bank-account/{bank_account}/status', [App\Http\Controllers\BankAccountController::class, 'status'])->name('bank-account.status');
    Route::resource('bank-account', App\Http\Controllers\BankAccountController::class)->except(['show']);
    // Program
    Route::post('program/{program}/status', [App\Http\Controllers\ProgramController::class, 'status'])->name('program.status');
    Route::resource('program', App\Http\Controllers\ProgramController::class)->except(['show']);
    // Period
    Route::post('period/{period}/status', [App\Http\Controllers\PeriodController::class, 'status'])->name('period.status');
    Route::resource('period', App\Http\Controllers\PeriodController::class)->except(['show']);
    // Coach
    Route::post('coach/{coach}/status', [App\Http\Controllers\CoachController::class, 'status'])->name('coach.status');
    Route::post('coach/{coach}/update', [App\Http\Controllers\CoachController::class, 'update'])->name('coach.update');
    Route::resource('coach', App\Http\Controllers\CoachController::class)->except(['update']);
    // Student
    Route::post('student/{student}/status', [App\Http\Controllers\StudentController::class, 'status'])->name('student.status');
    Route::post('student/{student}/update', [App\Http\Controllers\StudentController::class, 'update'])->name('student.update');
    Route::resource('student', App\Http\Controllers\StudentController::class)->except(['update']);
    // Registration Student
    Route::post('registration-student/{student_program}/payment', [App\Http\Controllers\RegistrationStudentController::class, 'payment'])->name('registration-student.payment');
    Route::post('registration-student/{student_program}/payment/status', [App\Http\Controllers\RegistrationStudentController::class, 'paymentStatus'])->name('registration-student.payment.status');
    Route::resource('registration-student', App\Http\Controllers\RegistrationStudentController::class);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
