<?php

namespace App\Http\Controllers;

use App\Enums\StatusCoach;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusTraining;
use App\Enums\Variant;
use App\Models\Coach;
use App\Models\Period;
use App\Models\Program;
use App\Models\Training;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class TrainingController extends Controller
{
    use HasPermissionCheck;

    protected $period_active;
    protected $periods = [];
    protected $programs = [];
    protected $coaches = [];
    protected $status_trainings = [];
    protected $variants = [];
    protected $attributes = [
        'student_id' => 'Siswa',
        'program_code' => 'Program Yang Diikuti',
        'period_id' => 'Periode Yang Diikuti',
    ];

    public function __construct()
    {
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
        $this->programs = Program::where('status', StatusProgram::ACTIVE)->get();
        $this->coaches = Coach::where('status', StatusCoach::ACTIVE)->get();
        $this->status_trainings = StatusTraining::options();
        $this->variants = Variant::options();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('training.index');

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

        return Inertia::render('training/Index', [
            'periods' => $this->periods,
            'status_trainings' => $this->status_trainings,
            'variants' => $this->variants,
            'trainings' => $trainings,
            'period_id_terms' => $period_id,
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
        $this->checkPermission('training.create');

        return Inertia::render('training/Create', [
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
        $this->checkPermission('training.create');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'coach_id' => ['required', 'exists:coach,id'],
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
                'coach_id' => $request->coach_id,
                'training_date' => $request->training_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusTraining::INACTIVE,
            ]);
            DB::commit();
            return redirect()->route('training.show', $training->id)->with('success', 'Latihan berhasil ditambahkan');
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
        $this->checkPermission('training.show');

        $training = Training::with(['period', 'program', 'coach'])->findOrFail($id);
        return Inertia::render('training/Show', [
            'status_trainings' => $this->status_trainings,
            'variants' => $this->variants,
            'training' => $training,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('training.edit');

        $training = Training::findOrFail($id);
        return Inertia::render('training/Edit', [
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
        $this->checkPermission('training.edit');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'coach_id' => ['required', 'exists:coach,id'],
            'training_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
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
            ]);
            DB::commit();
            return redirect()->route('training.show', $training->id)->with('success', 'Latihan berhasil diubah');
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
        $this->checkPermission('training.delete');

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($id);
            $training->delete();
            DB::commit();
            return redirect()->route('training.index')->with('success', 'Latihan berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Change Status the specified resource from storage.
     */
    public function status(string $id)
    {
        $this->checkPermission('training.edit');

        try {
            DB::beginTransaction();
            $training = Training::findOrFail($id);
            $training->update([
                'status' => $training->status === StatusTraining::ACTIVE ? StatusTraining::INACTIVE : StatusTraining::ACTIVE,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Status Latihan berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
