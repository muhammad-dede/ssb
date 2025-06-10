<?php

namespace App\Providers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\StudentProgram;
use App\Observers\PaymentObserver;
use App\Observers\StudentObserver;
use App\Observers\StudentProgramObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Student::observe(StudentObserver::class);
        StudentProgram::observe(StudentProgramObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
