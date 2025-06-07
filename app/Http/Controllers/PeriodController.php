<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Period;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PeriodController extends Controller
{
    use HasPermissionCheck;

    protected $statuses;
    protected $attributes = [
        'name' => 'Nama Periode',
        'start_date' => 'Tanggal Mulai',
        'end_date' => 'Tanggal Akhir',
    ];

    public function __construct()
    {
        $this->statuses = Status::options();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('period.index');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = $request->filter ?? 'desc';

        $periods = Period::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('period/Index', [
            'statuses' => $this->statuses,
            'periods' => $periods,
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
        $this->checkPermission('period.create');

        return Inertia::render('period/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('period.create');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
        ], $this->attributes);

        try {
            DB::beginTransaction();
            Period::create([
                'name' => strtoupper($request->name),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => Status::INACTIVE,
            ]);
            DB::commit();
            return redirect()->route('period.index')->with('success', 'Periode berhasil ditambahkan');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('period.edit');

        $period = Period::findOrFail($id);
        return Inertia::render('period/Edit', [
            'period' => $period,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('period.edit');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus setelah atau sama dengan tanggal mulai.',
        ], $this->attributes);

        try {
            DB::beginTransaction();
            $period = Period::findOrFail($id);
            $period->update([
                'name' => strtoupper($request->name),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);
            DB::commit();
            return redirect()->route('period.index')->with('success', 'Periode berhasil diubah');
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
        $this->checkPermission('period.delete');

        try {
            DB::beginTransaction();
            $period = Period::findOrFail($id);
            $period->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Periode berhasil dihapus');
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
        $this->checkPermission('period.edit');

        try {
            DB::beginTransaction();
            $period = Period::findOrFail($id);
            Period::where('status', Status::ACTIVE)->update([
                'status' => Status::INACTIVE,
            ]);
            $period->update([
                'status' => $period->status === Status::ACTIVE ? Status::INACTIVE : Status::ACTIVE,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Status Periode berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
