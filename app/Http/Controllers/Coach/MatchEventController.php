<?php

namespace App\Http\Controllers\Coach;

use App\Enums\Attendance;
use App\Enums\StatusAssessment;
use App\Enums\StatusMatchEvent;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\MatchEvent;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentMatchEvent;
use App\Models\StudentMatchEventAssessment;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MatchEventController extends Controller
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
        'match_date' => 'Tanggal Pertandingan',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'opponent' => 'Lawan',
        'our_score' => 'Skor Tim',
        'opponent_score' => 'Skor Lawan',
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

        $match_events = MatchEvent::query()
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

        return Inertia::render('coach/match-event/Index', [
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
        $this->checkPermission('coach-menu');

        return Inertia::render('coach/match-event/Create', [
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
            'match_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'opponent' => ['required', 'string', 'max:255'],
            'our_score' => ['required', 'numeric'],
            'opponent_score' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::create([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $this->coach->id,
                'match_date' => $request->match_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'opponent' => strtoupper($request->opponent),
                'our_score' => $request->our_score,
                'opponent_score' => $request->opponent_score,
                'location' => $request->location,
                'description' => $request->description,
                'status' => StatusMatchEvent::ACTIVE,
            ]);
            DB::commit();
            return redirect()->route('coach.match-event.show', $match_event->id)->with('success', 'Pertandingan berhasil ditambahkan');
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

        $match_event = MatchEvent::with(['period', 'program', 'coach'])
            ->where('coach_id', $this->coach->id)
            ->where('id', $id)
            ->firstOrFail();
        $student_match_events = StudentMatchEvent::with(['student', 'studentMatchEventAssessments', 'studentMatchEventAssessments.assessment'])
            ->where('match_event_id', $match_event->id)
            ->get();
        return Inertia::render('coach/match-event/Show', [
            'attendances' => $this->attendances,
            'assessments' => $this->assessments,
            'match_event' => $match_event,
            'student_match_events' => $student_match_events,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('coach-menu');

        $match_event = MatchEvent::where('coach_id', $this->coach->id)
            ->where('id', $id)
            ->firstOrFail();
        return Inertia::render('coach/match-event/Edit', [
            'programs' => $this->programs,
            'periods' => $this->periods,
            'match_event' => $match_event,
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
            'match_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time'   => ['required', 'date_format:H:i', 'after:start_time'],
            'opponent' => ['required', 'string', 'max:255'],
            'our_score' => ['required', 'numeric'],
            'opponent_score' => ['required', 'numeric'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::where('coach_id', $this->coach->id)
                ->where('id', $id)
                ->firstOrFail();
            $match_event->update([
                'period_id' => $request->period_id,
                'program_code' => $request->program_code,
                'coach_id' => $this->coach->id,
                'match_date' => $request->match_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'opponent' => strtoupper($request->opponent),
                'our_score' => $request->our_score,
                'opponent_score' => $request->opponent_score,
                'location' => $request->location,
                'description' => $request->description,
            ]);
            DB::commit();
            return redirect()->route('coach.match-event.show', $match_event->id)->with('success', 'Pertandingan berhasil diubah');
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
            $match_event = MatchEvent::where('coach_id', $this->coach->id)
                ->where('id', $id)
                ->firstOrFail();
            $match_event->delete();
            DB::commit();
            return redirect()->route('coach.match-event.index')->with('success', 'Pertandingan berhasil dihapus');
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
        $this->checkPermission('coach-menu');

        try {
            DB::beginTransaction();
            $match_event = MatchEvent::where('coach_id', $this->coach->id)
                ->where('id', $match_event_id)
                ->firstOrFail();
            $students = Student::whereHas(
                'programs',
                fn($query) =>
                $query->where('period_id', $match_event->period_id)
                    ->where('program_code', $match_event->program_code)
                    ->where('status', StatusStudentProgram::REGISTERED)
            )->get();
            foreach ($students as $student) {
                $student_match_event = $match_event->studentMatchEvents()->updateOrCreate([
                    'match_event_id' => $match_event->id,
                    'student_id' => $student->id,
                ]);
                foreach ($this->assessments as $assessment) {
                    $exists = $student_match_event->studentMatchEventAssessments()
                        ->where('assessment_code', $assessment->code)
                        ->exists();
                    if (!$exists) {
                        $student_match_event->studentMatchEventAssessments()->create([
                            'student_match_event_id' => $student_match_event->id,
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
                    StudentMatchEvent::where('id', $value['id'])->update([
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
                    StudentMatchEventAssessment::where('id', $value['id'])->update([
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
