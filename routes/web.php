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
    Route::resource('user', App\Http\Controllers\UserController::class)->except(['show']);
    // Bank Account
    Route::resource('bank-account', App\Http\Controllers\BankAccountController::class)->except(['show']);
    // Program
    Route::resource('program', App\Http\Controllers\ProgramController::class)->except(['show']);
    // Period
    Route::resource('period', App\Http\Controllers\PeriodController::class)->except(['show']);
    // Coach
    Route::post('coach/{coach}/update', [App\Http\Controllers\CoachController::class, 'update'])->name('coach.update');
    Route::resource('coach', App\Http\Controllers\CoachController::class)->except(['update']);
    // Student
    Route::post('student/{student}/update', [App\Http\Controllers\StudentController::class, 'update'])->name('student.update');
    Route::resource('student', App\Http\Controllers\StudentController::class)->except(['update']);
    // Registration Student
    Route::post('registration-student/{student_program}/payment', [App\Http\Controllers\RegistrationStudentController::class, 'payment'])->name('registration-student.payment');
    Route::post('registration-student/{student_program}/payment/status', [App\Http\Controllers\RegistrationStudentController::class, 'paymentStatus'])->name('registration-student.payment.status');
    Route::resource('registration-student', App\Http\Controllers\RegistrationStudentController::class);
    // Training
    Route::resource('training', App\Http\Controllers\TrainingController::class);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
