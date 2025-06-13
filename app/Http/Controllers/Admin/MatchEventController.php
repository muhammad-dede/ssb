<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Attendance;
use App\Enums\StatusAssessment;
use App\Enums\StatusCoach;
use App\Enums\StatusMatchEvent;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Enums\Variant;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Coach;
use App\Models\MatchEvent;
use App\Models\MatchEventAssessment;
use App\Models\MatchEventAttendance;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class MatchEventController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $variants = [];
    protected $status_match_events = [];
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
        'match_date' => 'Tanggal Pertandingan',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'opponent' => 'Lawan',
        'our_score' => 'Skor Tim',
        'opponent_score' => 'Skor Lawan',
        'location' => 'Lokasi',
        'description' => 'Deskripsi',
        'status' => 'Status',
    ];

    public function __construct()
    {
        // Enums
        $this->variants = Variant::options();
        $this->status_match_events = StatusMatchEvent::options();
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
        $this->checkPermission('admin.match-event.index');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $match_events = MatchEvent::query()
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

        return Inertia::render('admin/match-event/Index', [
            'variants' => $this->variants,
            'status_match_events' => $this->status_match_events,
            'periods' => $this->periods,
            'match_events' => $match_events,
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
        $this->checkPermission('admin.match-event.create');

        return Inertia::render('admin/match-event/Create', [
            'status_match_events' => $this->status_match_events,
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
        $this->checkPermission('admin.match-event.create');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'coach_id' => ['required', 'exists:coach,id'],
            'match_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'opponent' => ['required', 'string', 'max:255'],
            'our_score' => ['required', 'numeric'],
            'opponent_score' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(StatusMatchEvent::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::create([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $request->coach_id,
                'match_date' => $request->match_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'opponent' => strtoupper($request->opponent),
                'our_score' => $request->our_score,
                'opponent_score' => $request->opponent_score,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusMatchEvent::from($request->status),
            ]);
            DB::commit();
            return redirect()->route('admin.match-event.show', $match_event->id)->with('success', 'Pertandingan berhasil ditambahkan');
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
        $this->checkPermission('admin.match-event.show');

        $match_event = MatchEvent::with(['period', 'program', 'coach'])->findOrFail($id);
        $match_event_attendances = MatchEventAttendance::with(['student'])->where('match_event_id', $match_event->id)->get();
        $match_event_assessments = MatchEventAssessment::with(['student', 'assessment'])->where('match_event_id', $match_event->id)->get();
        return Inertia::render('admin/match-event/Show', [
            'variants' => $this->variants,
            'status_match_events' => $this->status_match_events,
            'attendances' => $this->attendances,
            'assessments' => $this->assessments,
            'match_event' => $match_event,
            'match_event_attendances' => $match_event_attendances,
            'match_event_assessments' => $match_event_assessments,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('admin.match-event.edit');

        $match_event = MatchEvent::with(['period', 'program', 'coach'])->findOrFail($id);
        return Inertia::render('admin/match-event/Edit', [
            'status_match_events' => $this->status_match_events,
            'programs' => $this->programs,
            'periods' => $this->periods,
            'coaches' => $this->coaches,
            'match_event' => $match_event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('admin.match-event.edit');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'program_code' => ['required', 'exists:program,code'],
            'coach_id' => ['required', 'exists:coach,id'],
            'match_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'opponent' => ['required', 'string', 'max:255'],
            'our_score' => ['required', 'numeric'],
            'opponent_score' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', new Enum(StatusMatchEvent::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::findOrFail($id);
            $match_event->update([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $request->coach_id,
                'match_date' => $request->match_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'opponent' => strtoupper($request->opponent),
                'our_score' => $request->our_score,
                'opponent_score' => $request->opponent_score,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusMatchEvent::from($request->status),
            ]);
            DB::commit();
            return redirect()->route('admin.match-event.show', $match_event->id)->with('success', 'Pertandingan berhasil diubah');
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
        $this->checkPermission('admin.match-event.delete');

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::findOrFail($id);
            $match_event->delete();
            DB::commit();
            return redirect()->route('admin.match-event.index')->with('success', 'Pertandingan berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Generate new resource from storage.
     */
    public function generate(string $match_event_id)
    {
        $this->checkPermission('admin.match-event.generate');

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::findOrFail($match_event_id);
            $students = Student::whereHas(
                'programs',
                fn($query) =>
                $query->where('period_id', $match_event->period_id)
                    ->where('program_code', $match_event->program_code)
                    ->where('status', StatusStudentProgram::ACTIVE)
            )->get();
            foreach ($students as $student) {
                $match_event->attendances()->updateOrCreate([
                    'match_event_id' => $match_event->id,
                    'student_id' => $student->id,
                ]);
                foreach ($this->assessments as $assessment) {
                    $exists = $match_event->assessments()
                        ->where('match_event_id', $match_event->id)
                        ->where('student_id', $student->id)
                        ->where('assessment_code', $assessment->code)
                        ->exists();
                    if (!$exists) {
                        $match_event->assessments()->create([
                            'match_event_id' => $match_event->id,
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
    public function attendance(Request $request, string $match_event_id)
    {
        $this->checkPermission('admin.match-event.attendance');

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::findOrFail($match_event_id);
            if (!empty($request->attendances)) {
                foreach ($request->attendances as $value) {
                    $match_event->attendances()
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
    public function assessment(Request $request, string $match_event_id)
    {
        $this->checkPermission('admin.match-event.assessment');

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::findOrFail($match_event_id);
            if (!empty($request->assessments)) {
                foreach ($request->assessments as $value) {
                    $match_event->assessments()
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
