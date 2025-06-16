<?php

namespace App\Http\Controllers\Coach;

use App\Enums\Attendance;
use App\Enums\StatusAssessment;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Enums\StatusTraining;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\Training;
use App\Models\StudentTraining;
use App\Models\StudentTrainingAssessment;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TrainingController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $attendances = [];
    // Models
    protected $coach;
    protected $period_active;
    protected $periods = [];
    protected $programs = [];
    protected $assessments = [];
    // Validation
    protected $attributes = [
        'period_id' => 'Periode Yang Diikuti',
        'program_code' => 'Program Yang Diikuti',
        'training_date' => 'Tanggal Latihan',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'location' => 'Lokasi',
        'description' => 'Deskripsi',
    ];

    public function __construct()
    {
        // Enums
        $this->attendances = Attendance::options();
        // Models
        $this->coach = Auth::user()->coach ?? null;
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
        $this->programs = Program::where('status', StatusProgram::ACTIVE)->get();
        $this->assessments = Assessment::where('status', StatusAssessment::ACTIVE)->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('coach-menu');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $trainings = Training::query()
            ->with(['program', 'period'])
            ->when($period_id, function ($query) use ($period_id) {
                $query->where('period_id', $period_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('program', function ($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%');
                    });
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->where('coach_id', $this->coach->id)
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('coach/training/Index', [
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
        $this->checkPermission('coach-menu');

        return Inertia::render('coach/training/Create', [
            'programs' => $this->programs,
            'periods' => $this->periods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('coach-menu');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'training_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $training = Training::create([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $this->coach->id,
                'training_date' => $request->training_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusTraining::ACTIVE,
            ]);
            DB::commit();
            return redirect()->route('coach.training.show', $training->id)->with('success', 'Latihan berhasil ditambahkan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->checkPermission('coach-menu');

        $training = Training::with(['period', 'program', 'coach'])
            ->where('coach_id', $this->coach->id)
            ->where('id', $id)
            ->firstOrFail();
        $student_trainings = StudentTraining::with([
            'student',
            'studentTrainingAssessments',
            'studentTrainingAssessments.assessment',
        ])
            ->where('training_id', $training->id)
            ->get();

        return Inertia::render('coach/training/Show', [
            'attendances' => $this->attendances,
            'assessments' => $this->assessments,
            'training' => $training,
            'student_trainings' => $student_trainings,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('coach-menu');

        $training = Training::where('coach_id', $this->coach->id)
            ->where('id', $id)
            ->firstOrFail();
        return Inertia::render('coach/training/Edit', [
            'programs' => $this->programs,
            'periods' => $this->periods,
            'training' => $training,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('coach-menu');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'training_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $training = Training::where('coach_id', $this->coach->id)
                ->where('id', $id)
                ->firstOrFail();
            $training->update([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $this->coach->id,
                'training_date' => $request->training_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'description' => $request->description,
            ]);
            DB::commit();
            return redirect()->route('coach.training.show', $training->id)->with('success', 'Latihan berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkPermission('coach-menu');

        try {
            DB::beginTransaction();
            $training = Training::where('coach_id', $this->coach->id)
                ->where('id', $id)
                ->firstOrFail();
            $training->delete();
            DB::commit();
            return redirect()->route('coach.training.index')->with('success', 'Latihan berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Generate new resource from storage.
     */
    public function generate(string $training_id)
    {
        $this->checkPermission('coach-menu');

        try {
            DB::beginTransaction();
            $training = Training::where('coach_id', $this->coach->id)
                ->where('id', $training_id)
                ->firstOrFail();
            $students = Student::whereHas(
                'programs',
                fn($query) =>
                $query->where('period_id', $training->period_id)
                    ->where('program_code', $training->program_code)
                    ->where('status', StatusStudentProgram::REGISTERED)
            )->get();
            foreach ($students as $student) {
                $student_training = $training->studentTrainings()->updateOrCreate([
                    'training_id' => $training->id,
                    'student_id' => $student->id,
                ]);
                foreach ($this->assessments as $assessment) {
                    $exists = $student_training->studentTrainingAssessments()
                        ->where('assessment_code', $assessment->code)
                        ->exists();
                    if (!$exists) {
                        $student_training->studentTrainingAssessments()->create([
                            'student_training_id' => $student_training->id,
                            'assessment_code' => $assessment->code,
                            'value' => 0,
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Generate berhasil');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function attendance(Request $request)
    {
        $this->checkPermission('coach-menu');

        try {
            DB::beginTransaction();
            if (!empty($request->attendances)) {
                foreach ($request->attendances as $value) {
                    StudentTraining::where('id', $value['id'])->update([
                        'attendance' => $value['attendance'],
                        'notes' => $value['notes'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Kehadiran berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function assessment(Request $request)
    {
        $this->checkPermission('coach-menu');

        try {
            DB::beginTransaction();
            if (!empty($request->assessments)) {
                foreach ($request->assessments as $value) {
                    StudentTrainingAssessment::where('id', $value['id'])->update([
                        'value' => $value['value'],
                    ]);
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Penilaian berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
