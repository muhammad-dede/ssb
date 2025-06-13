<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('admin')->as('admin.')->group(function () {
    // Role
    Route::resource('role', App\Http\Controllers\Admin\RoleController::class)->except(['show']);
    // User
    Route::resource('user', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    // Bank Account
    Route::resource('bank-account', App\Http\Controllers\Admin\BankAccountController::class)->except(['show']);
    // Program
    Route::resource('program', App\Http\Controllers\Admin\ProgramController::class)->except(['show']);
    // Period
    Route::resource('period', App\Http\Controllers\Admin\PeriodController::class)->except(['show']);
    // Coach
    Route::post('coach/{coach}/update', [App\Http\Controllers\Admin\CoachController::class, 'update'])->name('coach.update');
    Route::resource('coach', App\Http\Controllers\Admin\CoachController::class)->except(['update']);
    // Student
    Route::post('student/{student}/update', [App\Http\Controllers\Admin\StudentController::class, 'update'])->name('student.update');
    Route::resource('student', App\Http\Controllers\Admin\StudentController::class)->except(['update']);
    // Student Program
    Route::post('student-program/{student_program}/payment', [App\Http\Controllers\Admin\StudentProgramController::class, 'payment'])->name('student-program.payment');
    Route::post('student-program/{student_program}/payment/status', [App\Http\Controllers\Admin\StudentProgramController::class, 'paymentStatus'])->name('student-program.payment.status');
    Route::resource('student-program', App\Http\Controllers\Admin\StudentProgramController::class);
    // Training
    Route::post('training/{training}/generate', [App\Http\Controllers\Admin\TrainingController::class, 'generate'])->name('training.generate');
    Route::post('training/{training}/attendance', [App\Http\Controllers\Admin\TrainingController::class, 'attendance'])->name('training.attendance');
    Route::post('training/{training}/assessment', [App\Http\Controllers\Admin\TrainingController::class, 'assessment'])->name('training.assessment');
    Route::resource('training', App\Http\Controllers\Admin\TrainingController::class);
    // Match Event
    Route::post('match-event/{match_event}/generate', [App\Http\Controllers\Admin\MatchEventController::class, 'generate'])->name('match-event.generate');
    Route::post('match-event/{match_event}/attendance', [App\Http\Controllers\Admin\MatchEventController::class, 'attendance'])->name('match-event.attendance');
    Route::post('match-event/{match_event}/assessment', [App\Http\Controllers\Admin\MatchEventController::class, 'assessment'])->name('match-event.assessment');
    Route::resource('match-event', App\Http\Controllers\Admin\MatchEventController::class);
    // Report Student
    Route::get('report-student', [App\Http\Controllers\Admin\ReportStudentController::class, 'index'])->name('report-student.index');
    Route::get('report-student/{student_program}', [App\Http\Controllers\Admin\ReportStudentController::class, 'show'])->name('report-student.show');
    Route::get('report-student/{student_program}/pdf', [App\Http\Controllers\Admin\ReportStudentController::class, 'generatePdf'])->name('report-student.pdf');
});
