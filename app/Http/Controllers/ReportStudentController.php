<?php

namespace App\Http\Controllers;

use App\Enums\Attendance;
use App\Enums\StatusAssessment;
use App\Enums\StatusMatchEvent;
use App\Enums\StatusPeriod;
use App\Enums\StatusStudentProgram;
use App\Enums\StatusTraining;
use App\Enums\Variant;
use App\Models\Assessment;
use App\Models\MatchEvent;
use App\Models\MatchEventAssessment;
use App\Models\MatchEventAttendance;
use App\Models\Period;
use App\Models\Program;
use App\Models\StudentProgram;
use App\Models\Training;
use App\Models\TrainingAssessment;
use App\Models\TrainingAttendance;
use App\Traits\HasPermissionCheck;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportStudentController extends Controller
{
    use HasPermissionCheck;
    // Enums
    protected $variants = [];
    protected $status_student_programs = [];
    // Models
    protected $period_active;
    protected $periods = [];
    protected $programs = [];
    protected $assessments = [];

    public function __construct()
    {
        // Enums
        $this->variants = Variant::options();
        $this->status_student_programs = StatusStudentProgram::options();
        // Models
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
        $this->programs = Program::orderBy('code', 'asc')->get();
        $this->assessments = Assessment::where('status', StatusAssessment::ACTIVE)->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('report-student.index');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $program_code = $request->program_code ?? "";
        $search = $request->search;
        $per_page = $request->per_page ?? "25";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $student_programs = StudentProgram::query()
            ->with(['student', 'program'])
            ->when($period_id, fn($query) => $query->where('period_id', $period_id))
            ->when($program_code, fn($query) => $query->where('program_code', $program_code))
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->where('status', StatusStudentProgram::ACTIVE)
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

        return Inertia::render('report-student/Index', [
            'variants' => $this->variants,
            'status_student_programs' => $this->status_student_programs,
            'periods' => $this->periods,
            'programs' => $this->programs,
            'student_programs' => $student_programs,
            'period_id_term' => $period_id,
            'program_code_terms' => $program_code,
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
        $this->checkPermission('report-student.show');

        $student_program = StudentProgram::with(['student', 'period', 'program'])->findOrFail($student_program_id);
        $student_program->report = $this->calculateReport($student_program->student_id, $student_program->period_id, $student_program->program_code);
        return Inertia::render('report-student/Show', [
            'student_program' => $student_program,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function generatePdf(string $student_program_id)
    {
        $this->checkPermission('report-student.show');

        $student_program = StudentProgram::with(['student', 'period', 'program'])->findOrFail($student_program_id);
        $student_program->report = $this->calculateReport($student_program->student_id, $student_program->period_id, $student_program->program_code);
        $pdf = Pdf::loadView('pdf.report-student', [
            'student_program' => $student_program,
        ])->setPaper('a4', 'portrait');
        $filename = 'raport_' . str_replace(' ', '_', $student_program->student?->name ?? 'NA') . '.pdf';
        return $pdf->stream($filename);
    }

    private function calculateReport($student_id, $period_id, $program_code)
    {
        // ========== training_attendance_percentage ==========
        $total_training = Training::where('period_id', $period_id)
            ->where('program_code', $program_code)
            ->where('status', StatusTraining::ACTIVE)
            ->count();

        $training_present = TrainingAttendance::whereHas('training', function ($q) use ($period_id, $program_code) {
            $q->where('period_id', $period_id)
                ->where('program_code', $program_code)
                ->where('status', StatusTraining::ACTIVE);
        })
            ->where('student_id', $student_id)
            ->where('attendance', Attendance::PRESENT)
            ->count();

        $training_attendance_percentage = $total_training > 0 ? round(($training_present / $total_training) * 100) : 0;

        // ========== match_event_attendance_percentage ==========
        $total_match_event = MatchEvent::where('period_id', $period_id)
            ->where('program_code', $program_code)
            ->where('status', StatusMatchEvent::ACTIVE)
            ->count();

        $match_event_present = MatchEventAttendance::whereHas('matchEvent', function ($q) use ($period_id, $program_code) {
            $q->where('period_id', $period_id)
                ->where('program_code', $program_code)
                ->where('status', StatusMatchEvent::ACTIVE);
        })
            ->where('student_id', $student_id)
            ->where('attendance', Attendance::PRESENT)
            ->count();

        $match_event_attendance_percentage = $total_match_event > 0 ? round(($match_event_present / $total_match_event) * 100) : 0;

        // ========== training_scores ==========
        $training_scores = TrainingAssessment::with('assessment')
            ->where('student_id', $student_id)
            ->whereHas('training', function ($q) use ($period_id, $program_code) {
                $q->where('period_id', $period_id)
                    ->where('program_code', $program_code)
                    ->where('status', StatusTraining::ACTIVE);
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

        // ========== match_event_scores ==========
        $match_event_scores = MatchEventAssessment::with('assessment')
            ->where('student_id', $student_id)
            ->whereHas('matchEvent', function ($q) use ($period_id, $program_code) {
                $q->where('period_id', $period_id)
                    ->where('program_code', $program_code)
                    ->where('status', StatusMatchEvent::ACTIVE);
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
