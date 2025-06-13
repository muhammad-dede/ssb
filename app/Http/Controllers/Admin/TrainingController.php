<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Attendance;
use App\Enums\StatusAssessment;
use App\Enums\StatusCoach;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Enums\StatusTraining;
use App\Enums\Variant;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Coach;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\Training;
use App\Models\TrainingAssessment;
use App\Models\TrainingAttendance;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class TrainingController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $variants = [];
    protected $status_trainings = [];
    protected $attendances = [];
    // Models
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
        $this->variants = Variant::options();
        $this->status_trainings = StatusTraining::options();
        $this->attendances = Attendance::options();
        // Models
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
        $this->checkPermission('admin.training.index');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $trainings = Training::query()
            ->with(['program', 'period', 'coach'])
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
                $query->orderBy('id', $filter);
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('admin/training/Index', [
            'variants' => $this->variants,
            'status_trainings' => $this->status_trainings,
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
        $this->checkPermission('admin.training.create');

        return Inertia::render('admin/training/Create', [
            'status_trainings' => $this->status_trainings,
            'programs' => $this->programs,
            'periods' => $this->periods,
            'coaches' => $this->coaches,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('admin.training.create');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'coach_id' => ['required', 'exists:coach,id'],
            'training_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(StatusTraining::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $training = Training::create([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $request->coach_id,
                'training_date' => $request->training_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusTraining::from($request->status),
            ]);
            DB::commit();
            return redirect()->route('admin.training.show', $training->id)->with('success', 'Latihan berhasil ditambahkan');
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
        $this->checkPermission('admin.training.show');

        $training = Training::with(['period', 'program', 'coach'])->findOrFail($id);
        $training_attendances = TrainingAttendance::with(['student'])->where('training_id', $training->id)->get();
        $training_assessments = TrainingAssessment::with(['student', 'assessment'])->where('training_id', $training->id)->get();
        return Inertia::render('admin/training/Show', [
            'variants' => $this->variants,
            'status_trainings' => $this->status_trainings,
            'attendances' => $this->attendances,
            'assessments' => $this->assessments,
            'training' => $training,
            'training_attendances' => $training_attendances,
            'training_assessments' => $training_assessments,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('admin.training.edit');

        $training = Training::findOrFail($id);
        return Inertia::render('admin/training/Edit', [
            'status_trainings' => $this->status_trainings,
            'programs' => $this->programs,
            'periods' => $this->periods,
            'coaches' => $this->coaches,
            'training' => $training,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('admin.training.edit');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'coach_id' => ['required', 'exists:coach,id'],
            'training_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(StatusTraining::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($id);
            $training->update([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $request->coach_id,
                'training_date' => $request->training_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusTraining::from($request->status),
            ]);
            DB::commit();
            return redirect()->route('admin.training.show', $training->id)->with('success', 'Latihan berhasil diubah');
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
        $this->checkPermission('admin.training.delete');

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($id);
            $training->delete();
            DB::commit();
            return redirect()->route('admin.training.index')->with('success', 'Latihan berhasil dihapus');
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
        $this->checkPermission('admin.training.generate');

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($training_id);
            $students = Student::whereHas(
                'programs',
                fn($query) =>
                $query->where('period_id', $training->period_id)
                    ->where('program_code', $training->program_code)
                    ->where('status', StatusStudentProgram::ACTIVE)
            )->get();
            foreach ($students as $student) {
                $training->attendances()->updateOrCreate([
                    'training_id' => $training->id,
                    'student_id' => $student->id,
                ]);
                foreach ($this->assessments as $assessment) {
                    $exists = $training->assessments()
                        ->where('training_id', $training->id)
                        ->where('student_id', $student->id)
                        ->where('assessment_code', $assessment->code)
                        ->exists();
                    if (!$exists) {
                        $training->assessments()->create([
                            'training_id' => $training->id,
                            'student_id' => $student->id,
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
    public function attendance(Request $request, string $training_id)
    {
        $this->checkPermission('admin.training.attendance');

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($training_id);
            if (!empty($request->attendances)) {
                foreach ($request->attendances as $value) {
                    $training->attendances()
                        ->where('student_id', $value['student_id'])
                        ->update([
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
    public function assessment(Request $request, string $training_id)
    {
        $this->checkPermission('admin.training.assessment');

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($training_id);
            if (!empty($request->assessments)) {
                foreach ($request->assessments as $value) {
                    $training->assessments()
                        ->where('student_id', $value['student_id'])
                        ->where('assessment_code', $value['assessment_code'])
                        ->update([
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
