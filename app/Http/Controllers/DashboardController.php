<?php

namespace App\Http\Controllers;

use App\Enums\StatusCoach;
use App\Enums\StatusMatchEvent;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusTraining;
use App\Models\Coach;
use App\Models\MatchEvent;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\Training;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use HasPermissionCheck;

    public function index(Request $request)
    {
        $this->checkPermission('dashboard');

        $period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $count_student_registered = 0;
        $count_student_unregistered = 0;
        $count_program = 0;
        $count_coach = 0;
        $training_schedules = [];
        $match_event_schedules = [];

        if (Auth::user()->hasRole(['Super Admin', 'Admin', 'Leader'])) {
            $count_student_unregistered = Student::whereDoesntHave('programs', function ($query) {
                $query->where('status', StatusProgram::ACTIVE);
            })->count();
            $count_student_registered = Student::whereHas('programs', function ($query) {
                $query->where('status', StatusProgram::ACTIVE);
            })->count();
            $count_program = Program::where('status', StatusProgram::ACTIVE)->count();
            $count_coach = Coach::where('status', StatusCoach::ACTIVE)->count();
            $training_schedules = Training::with(['coach'])->where('status', StatusTraining::ACTIVE)
                ->whereMonth('training_date', Carbon::now()->month)
                ->whereYear('training_date', Carbon::now()->year)
                ->get();
            $match_event_schedules = MatchEvent::with(['coach'])->where('status', StatusMatchEvent::ACTIVE)
                ->whereMonth('match_date', Carbon::now()->month)
                ->whereYear('match_date', Carbon::now()->year)
                ->get();
        }

        return Inertia::render('dashboard/Index', [
            'admin' => [
                'period_active' => $period_active,
                'count_student_unregistered' => $count_student_unregistered,
                'count_student_registered' => $count_student_registered,
                'count_program' => $count_program,
                'count_coach' => $count_coach,
                'training_schedules' => $training_schedules,
                'match_event_schedules' => $match_event_schedules,
            ],
        ]);
    }
}
