<?php

namespace App\Http\Controllers\Student;

use App\Enums\Attendance;
use App\Enums\StatusAssessment;
use App\Enums\StatusCoach;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusTraining;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Coach;
use App\Models\Period;
use App\Models\Program;
use App\Models\Training;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TrainingController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $attendances = [];
    // Models
    protected $student;
    protected $period_active;
    protected $periods = [];
    protected $programs = [];
    protected $coaches = [];
    protected $assessments = [];
    // Validation
    protected $attributes = [
        'period_id' => 'Periode Yang Diikuti',
        'program_code' => 'Program Yang Diikuti',
        'coach_id' => 'Pelatih',
        'training_date' => 'Tanggal Latihan',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'location' => 'Lokasi',
        'description' => 'Deskripsi',
        'status' => 'Status',
    ];

    public function __construct()
    {
        // Enums
        $this->attendances = Attendance::options();
        // Models
        $this->student = Auth::user()?->student;
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
        $this->programs = Program::where('status', StatusProgram::ACTIVE)->get();
        $this->coaches = Coach::where('status', StatusCoach::ACTIVE)->get();
        $this->assessments = Assessment::where('status', StatusAssessment::ACTIVE)->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('student-menu');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $search = $request->search;
        $per_page = $request->per_page ?? "10";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'asc';

        $trainings = Training::query()
            ->with(['program', 'period', 'coach'])
            ->whereHas('attendances', function ($query) {
                $query->where('student_id', $this->student->id);
            })
            ->when($period_id, function ($query) use ($period_id) {
                $query->where('period_id', $period_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('program', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    })->orWhereHas('coach', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('training_date', $filter);
            })
            ->where('status', StatusTraining::ACTIVE)
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('student/training/Index', [
            'periods' => $this->periods,
            'trainings' => $trainings,
            'period_id_term' => $period_id,
            'search_term' => $search,
            'per_page_term' => $per_page,
            'filter_term' => $filter,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
