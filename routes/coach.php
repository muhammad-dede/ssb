<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('coach')->as('coach.')->group(function () {
    // Training
    Route::post('training/{training}/generate', [App\Http\Controllers\Coach\TrainingController::class, 'generate'])->name('training.generate');
    Route::post('training/attendance', [App\Http\Controllers\Coach\TrainingController::class, 'attendance'])->name('training.attendance');
    Route::post('training/assessment', [App\Http\Controllers\Coach\TrainingController::class, 'assessment'])->name('training.assessment');
    Route::resource('training', App\Http\Controllers\Coach\TrainingController::class);
    // Match Event
    Route::post('match-event/{match_event}/generate', [App\Http\Controllers\Coach\MatchEventController::class, 'generate'])->name('match-event.generate');
    Route::post('match-event/attendance', [App\Http\Controllers\Coach\MatchEventController::class, 'attendance'])->name('match-event.attendance');
    Route::post('match-event/assessment', [App\Http\Controllers\Coach\MatchEventController::class, 'assessment'])->name('match-event.assessment');
    Route::resource('match-event', App\Http\Controllers\Coach\MatchEventController::class);
});
