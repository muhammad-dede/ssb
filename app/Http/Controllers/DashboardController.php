<?php

namespace App\Http\Controllers;

use App\Enums\StatusCoach;
use App\Enums\StatusMatchEvent;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Enums\StatusTraining;
use App\Models\Coach;
use App\Models\MatchEvent;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentMatchEvent;
use App\Models\StudentProgram;
use App\Models\StudentTraining;
use App\Models\Training;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    use HasPermissionCheck;

    public function index(Request $request)
    {
        $this->checkPermission('dashboard');

        $period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        // Admin
        $count_student_registered = 0;
        $count_student_unregistered = 0;
        $count_program = 0;
        $count_coach = 0;
        // Student
        $count_unregistered = 0;
        $count_registered = 0;
        $count_training = 0;
        $count_match_event = 0;
        // Coach
        $count_training_unregistered = 0;
        $count_training_registered = 0;
        $count_match_event_unregistered = 0;
        $count_match_event_registered = 0;

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
        } else if (Auth::user()->hasRole('Student')) {
            $student = Auth::user()->student;
            $count_unregistered = StudentProgram::where('student_id', $student->id)
                ->where('status', StatusStudentProgram::UNREGISTERED)
                ->count();
            $count_registered = StudentProgram::where('student_id', $student->id)
                ->where('status', StatusStudentProgram::REGISTERED)
                ->count();
            $count_training = StudentTraining::where('student_id', $student->id)->whereHas('training', function ($query) {
                $query->where('status', StatusTraining::ACTIVE);
            })->count();
            $count_match_event = StudentMatchEvent::where('student_id', $student->id)->whereHas('matchEvent', function ($query) {
                $query->where('status', StatusMatchEvent::ACTIVE);
            })->count();
            $training_schedules = Training::with([
                'coach',
                'studentTrainings'
            ])->whereHas('studentTrainings', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
                ->where('status', StatusTraining::ACTIVE)
                ->whereMonth('training_date', Carbon::now()->month)
                ->whereYear('training_date', Carbon::now()->year)
                ->get();
            $match_event_schedules = MatchEvent::with([
                'coach',
                'studentMatchEvents'
            ])->whereHas('studentMatchEvents', function ($query) use ($student) {
                $query->where('student_id', $student->id);
            })
                ->where('status', StatusMatchEvent::ACTIVE)
                ->whereMonth('match_date', Carbon::now()->month)
                ->whereYear('match_date', Carbon::now()->year)
                ->get();
        } else if (Auth::user()->hasRole('Coach')) {
            $coach = Auth::user()->coach;

            $trainings = Training::where('status', StatusTraining::ACTIVE)
                ->where('coach_id', $coach->id)
                ->select('program_code', 'period_id')
                ->distinct()
                ->get();
            foreach ($trainings as $training) {
                $count_unregis = StudentProgram::where('program_code', $training->program_code)
                    ->where('period_id', $training->period_id)
                    ->whereNotIn('student_id', function ($query) {
                        $query->select('student_id')->from('student_training');
                    })
                    ->count();
                $count_training_unregistered += $count_unregis;

                $count_regis =  StudentProgram::where('program_code', $training->program_code)
                    ->where('period_id', $training->period_id)
                    ->whereIn('student_id', function ($query) {
                        $query->select('student_id')->from('student_training');
                    })
                    ->count();
                $count_training_registered += $count_regis;
            }

            $match_events = MatchEvent::where('status', StatusMatchEvent::ACTIVE)
                ->where('coach_id', $coach->id)
                ->select('program_code', 'period_id')
                ->distinct()
                ->get();
            foreach ($match_events as $training) {
                $count_unregis = StudentProgram::where('program_code', $training->program_code)
                    ->where('period_id', $training->period_id)
                    ->whereNotIn('student_id', function ($query) {
                        $query->select('student_id')->from('student_training');
                    })
                    ->count();
                $count_match_event_unregistered += $count_unregis;

                $count_regis =  StudentProgram::where('program_code', $training->program_code)
                    ->where('period_id', $training->period_id)
                    ->whereIn('student_id', function ($query) {
                        $query->select('student_id')->from('student_training');
                    })
                    ->count();
                $count_match_event_registered += $count_regis;
            }

            $training_schedules = Training::with(['coach'])->where('status', StatusTraining::ACTIVE)
                ->whereMonth('training_date', Carbon::now()->month)
                ->whereYear('training_date', Carbon::now()->year)
                ->where('coach_id', $coach->id)
                ->get();
            $match_event_schedules = MatchEvent::with(['coach'])->where('status', StatusMatchEvent::ACTIVE)
                ->whereMonth('match_date', Carbon::now()->month)
                ->whereYear('match_date', Carbon::now()->year)
                ->where('coach_id', $coach->id)
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
            'student' => [
                'period_active' => $period_active,
                'count_unregistered' => $count_unregistered,
                'count_registered' => $count_registered,
                'count_training' => $count_training,
                'count_match_event' => $count_match_event,
                'training_schedules' => $training_schedules,
                'match_event_schedules' => $match_event_schedules,
            ],
            'coach' => [
                'period_active' => $period_active,
                'count_training_unregistered' => $count_training_unregistered,
                'count_training_registered' => $count_training_registered,
                'count_match_event_unregistered' => $count_match_event_unregistered,
                'count_match_event_registered' => $count_match_event_registered,
                'training_schedules' => $training_schedules,
                'match_event_schedules' => $match_event_schedules,
            ],
        ]);
    }
}
