<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return 'Home';
})->name('home');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard')->middleware('has_student');
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
    // Student Program
    Route::post('student-program/{student_program}/payment', [App\Http\Controllers\StudentProgramController::class, 'payment'])->name('student-program.payment');
    Route::post('student-program/{student_program}/payment/status', [App\Http\Controllers\StudentProgramController::class, 'paymentStatus'])->name('student-program.payment.status');
    Route::resource('student-program', App\Http\Controllers\StudentProgramController::class);
    // Training
    Route::post('training/{training}/generate', [App\Http\Controllers\TrainingController::class, 'generate'])->name('training.generate');
    Route::post('training/{training}/attendance', [App\Http\Controllers\TrainingController::class, 'attendance'])->name('training.attendance');
    Route::post('training/{training}/assessment', [App\Http\Controllers\TrainingController::class, 'assessment'])->name('training.assessment');
    Route::resource('training', App\Http\Controllers\TrainingController::class);
    // Match Event
    Route::post('match-event/{match_event}/generate', [App\Http\Controllers\MatchEventController::class, 'generate'])->name('match-event.generate');
    Route::post('match-event/{match_event}/attendance', [App\Http\Controllers\MatchEventController::class, 'attendance'])->name('match-event.attendance');
    Route::post('match-event/{match_event}/assessment', [App\Http\Controllers\MatchEventController::class, 'assessment'])->name('match-event.assessment');
    Route::resource('match-event', App\Http\Controllers\MatchEventController::class);
    // Report Student
    Route::get('report-student', [App\Http\Controllers\ReportStudentController::class, 'index'])->name('report-student.index');
    Route::get('report-student/{student_program}', [App\Http\Controllers\ReportStudentController::class, 'show'])->name('report-student.show');
    Route::get('report-student/{student_program}/pdf', [App\Http\Controllers\ReportStudentController::class, 'generatePdf'])->name('report-student.pdf');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/student.php';
