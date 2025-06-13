<?php

use App\Http\Controllers\Student\StudentProgramController;
use Illuminate\Support\Facades\Route;

Route::controller(App\Http\Controllers\EnsureStudentController::class)->group(function () {
    Route::get('ensure/student', 'create')->name('ensure.student');
    Route::post('ensure/student', 'store')->name('ensure.student.store');
})->middleware('auth');

Route::middleware(['auth', 'has_student'])
    ->prefix('student')
    ->as('student.')
    ->group(function () {
        Route::controller(StudentProgramController::class)->group(function () {
            Route::get('program', 'index')->name('program.index');
        });
    });
