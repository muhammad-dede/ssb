<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('student')->as('student.')->group(function () {
    Route::controller(App\Http\Controllers\Student\EnsureStudentController::class)->group(function () {
        Route::get('ensure', 'create')->name('ensure');
        Route::post('ensure', 'store')->name('ensure.store');
    });

    Route::middleware(['has_student'])->group(function () {
        Route::resource('student-program', App\Http\Controllers\Student\StudentProgramController::class);
    });
});
