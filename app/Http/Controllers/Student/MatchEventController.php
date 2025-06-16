<?php

namespace App\Http\Controllers\Student;

use App\Enums\StatusMatchEvent;
use App\Enums\StatusPeriod;
use App\Http\Controllers\Controller;
use App\Models\MatchEvent;
use App\Models\Period;
use App\Models\StudentMatchEvent;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MatchEventController extends Controller
{
    use HasPermissionCheck;

    // Models
    protected $student;
    protected $period_active;
    protected $periods = [];

    public function __construct()
    {
        // Models
        $this->student = Auth::user()?->student;
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('student-menu');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $search = $request->search;
        $per_page = $request->per_page ?? "25";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'asc';

        $student_match_events = StudentMatchEvent::with([
            'matchEvent',
            'matchEvent.coach',
            'studentMatchEventAssessments',
            'studentMatchEventAssessments.assessment'
        ])
            ->whereHas('matchEvent', function ($query) use ($search) {
                $query->where('status', StatusMatchEvent::ACTIVE)
                    ->when($search, function ($q) use ($search) {
                        $q->whereHas('coach', function ($q2) use ($search) {
                            $q2->where('name', 'like', '%' . $search . '%');
                        })
                            ->orWhere('location', 'like', '%' . $search . '%');
                    });
            })
            ->where('student_id', $this->student->id)
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy(
                    MatchEvent::select('match_date')
                        ->whereColumn('match_event.id', 'student_match_event.match_event_id'),
                    $filter
                );
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('student/match-event/Index', [
            'periods' => $this->periods,
            'student_match_events' => $student_match_events,
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
    public function show(string $student_match_event_id)
    {
        $this->checkPermission('student-menu');

        $student_match_event = StudentMatchEvent::with([
            'matchEvent',
            'matchEvent.coach',
            'matchEvent.coach',
            'studentMatchEventAssessments',
            'studentMatchEventAssessments.assessment'
        ])
            ->where('id', $student_match_event_id)
            ->where('student_id', $this->student->id)
            ->firstOrFail();

        return Inertia::render('student/match-event/Show', [
            'student_match_event' => $student_match_event,
        ]);
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
