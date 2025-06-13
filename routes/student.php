<?php

use App\Http\Controllers\Student\StudentProgramController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->prefix('student')->as('student.')->group(function () {
    Route::controller(App\Http\Controllers\Student\EnsureStudentController::class)->group(function () {
        Route::get('ensure', 'create')->name('ensure');
        Route::post('ensure', 'store')->name('ensure.store');
    });

    Route::middleware(['has_student'])->group(function () {
        Route::controller(StudentProgramController::class)->group(function () {
            Route::get('student-program', 'index')->name('student-program.index');
        });
    });
});
