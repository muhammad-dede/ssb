<?php

namespace App\Http\Controllers\Student;

use App\Enums\Attendance;
use App\Enums\StatusPeriod;
use App\Enums\StatusStudentProgram;
use App\Enums\StatusTraining;
use App\Http\Controllers\Controller;
use App\Models\MatchEvent;
use App\Models\Period;
use App\Models\Program;
use App\Models\StudentMatchEvent;
use App\Models\StudentMatchEventAssessment;
use App\Models\StudentProgram;
use App\Models\StudentTraining;
use App\Models\StudentTrainingAssessment;
use App\Models\Training;
use App\Traits\HasPermissionCheck;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ReportController extends Controller
{
    use HasPermissionCheck;
    // Models
    protected $student;
    protected $period_active;

    public function __construct()
    {
        $this->student = Auth::user()?->student;
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('student-menu');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $student_programs = StudentProgram::query()
            ->with(['program', 'period'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('program', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('period', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->where('status', StatusStudentProgram::REGISTERED)
            ->where('student_id', $this->student->id)
            ->orderBy('id', $filter)
            ->paginate($per_page)
            ->withQueryString();

        $student_programs->getCollection()->transform(function ($student_program) {
            $report = $this->calculateReport($student_program->student_id, $student_program->period_id, $student_program->program_code);

            return [
                ...$student_program->toArray(),
                'report' => $report,
            ];
        });

        return Inertia::render('student/report/Index', [
            'student_programs' => $student_programs,
            'search_term' => $search,
            'per_page_term' => $per_page,
            'filter_term' => $filter,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $student_program_id)
    {
        $this->checkPermission('student-menu');

        $student_program = StudentProgram::with([
            'student',
            'period',
            'program'
        ])
            ->where('student_id', $this->student->id)
            ->where('id', $student_program_id)
            ->firstOrFail();
        $student_program->report = $this->calculateReport($student_program->student_id, $student_program->period_id, $student_program->program_code);
        return Inertia::render('student/report/Show', [
            'student_program' => $student_program,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function generatePdf(string $student_program_id)
    {
        $this->checkPermission('student-menu');

        $student_program = StudentProgram::with([
            'student',
            'period',
            'program'
        ])
            ->where('student_id', $this->student->id)
            ->where('id', $student_program_id)
            ->firstOrFail();
        $student_program->report = $this->calculateReport($student_program->student_id, $student_program->period_id, $student_program->program_code);
        $pdf = Pdf::loadView('pdf.report-student', [
            'student_program' => $student_program,
        ])->setPaper('a4', 'portrait');
        $filename = 'raport_' . str_replace(' ', '_', $student_program->student?->name ?? 'NA') . '.pdf';
        return $pdf->stream($filename);
    }

    private function calculateReport($student_id, $period_id, $program_code)
    {
        // ========== Training ========== //
        $total_training = Training::where('period_id', $period_id)
            ->where('program_code', $program_code)
            ->where('status', StatusTraining::ACTIVE)
            ->count();
        $training_present = StudentTraining::whereHas('training', function ($q) use ($period_id, $program_code) {
            $q->where('period_id', $period_id)
                ->where('program_code', $program_code)
                ->where('status', StatusTraining::ACTIVE);
        })
            ->where('student_id', $student_id)
            ->where('attendance', Attendance::PRESENT)
            ->count();
        $training_attendance_percentage = $total_training > 0 ? round(($training_present / $total_training) * 100) : 0;

        $training_scores = StudentTrainingAssessment::with('assessment')
            ->whereHas('studentTraining', function ($query) use ($student_id, $period_id, $program_code) {
                $query->where('student_id', $student_id)
                    ->whereHas('training', function ($q) use ($period_id, $program_code) {
                        $q->where('period_id', $period_id)
                            ->where('program_code', $program_code)
                            ->where('status', StatusTraining::ACTIVE);
                    });
            })
            ->get()
            ->groupBy('assessment_code')
            ->map(function ($items, $code) {
                return [
                    'code' => $code,
                    'name' => optional($items->first()->assessment)->name ?? '-',
                    'total_value' => round($items->avg('value')),
                ];
            })
            ->values();
        $training_avg_assessment = $training_scores->pluck('total_value')->avg() ?? 0;
        $training_scores->push([
            'code' => 'ATD',
            'name' => 'KEHADIRAN',
            'total_value' => $training_attendance_percentage,
        ]);
        $training_total_score = round(($training_attendance_percentage * 0.4) + ($training_avg_assessment * 0.6));

        // ========== Match Event ========== //
        $total_match_event = MatchEvent::where('period_id', $period_id)
            ->where('program_code', $program_code)
            ->where('status', StatusTraining::ACTIVE)
            ->count();
        $match_event_present = StudentMatchEvent::whereHas('matchEvent', function ($q) use ($period_id, $program_code) {
            $q->where('period_id', $period_id)
                ->where('program_code', $program_code)
                ->where('status', StatusTraining::ACTIVE);
        })
            ->where('student_id', $student_id)
            ->where('attendance', Attendance::PRESENT)
            ->count();
        $match_event_attendance_percentage = $total_match_event > 0 ? round(($match_event_present / $total_match_event) * 100) : 0;

        $match_event_scores = StudentMatchEventAssessment::with('assessment')
            ->whereHas('studentMatchEvent', function ($query) use ($student_id, $period_id, $program_code) {
                $query->where('student_id', $student_id)
                    ->whereHas('matchEvent', function ($q) use ($period_id, $program_code) {
                        $q->where('period_id', $period_id)
                            ->where('program_code', $program_code)
                            ->where('status', StatusTraining::ACTIVE);
                    });
            })
            ->get()
            ->groupBy('assessment_code')
            ->map(function ($items, $code) {
                return [
                    'code' => $code,
                    'name' => optional($items->first()->assessment)->name ?? '-',
                    'total_value' => round($items->avg('value')),
                ];
            })
            ->values();
        $match_event_avg_assessment = $match_event_scores->pluck('total_value')->avg() ?? 0;
        $match_event_scores->push([
            'code' => 'ATD',
            'name' => 'KEHADIRAN',
            'total_value' => $match_event_attendance_percentage,
        ]);
        $match_event_total_score = round(($match_event_attendance_percentage * 0.4) + ($match_event_avg_assessment * 0.6));

        // ========== final_score (average of total training & match_event) ==========
        $final_score = round(($training_total_score + $match_event_total_score) / 2);

        return [
            'training' => [
                'scores' => $training_scores,
                'total_score' => $training_total_score,
            ],
            'match_event' => [
                'scores' => $match_event_scores,
                'total_score' => $match_event_total_score,
            ],
            'final_score' => $final_score,
        ];
    }
}
