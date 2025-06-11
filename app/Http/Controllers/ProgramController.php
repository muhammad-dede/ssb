<?php

namespace App\Http\Controllers;

use App\Enums\StatusProgram;
use App\Enums\Variant;
use App\Models\Program;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class ProgramController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $variants = [];
    protected $status_programs = [];
    // Validation
    protected $attributes = [
        'code' => 'Kode',
        'name' => 'Nama',
        'age_min' => 'Minimal Usia',
        'age_max' => 'Maksimal Usia',
        'description' => 'Deskripsi',
        'registration_fee' => 'Biaya Registrasi',
        'status' => 'Status',
    ];

    public function __construct()
    {
        $this->variants = Variant::options();
        $this->status_programs = StatusProgram::options();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('program.index');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $programs = Program::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', '%' . $search . '%')
                        ->orWhere('name', 'like', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('program/Index', [
            'variants' => $this->variants,
            'status_programs' => $this->status_programs,
            'programs' => $programs,
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
        $this->checkPermission('program.create');

        return Inertia::render('program/Create', [
            'status_programs' => $this->status_programs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('program.create');

        $request->validate([
            'code' => ['required', 'max:20', 'unique:program,code', 'regex:/^\S*$/'],
            'name' => ['required', 'string', 'max:255'],
            'age_min' => ['required', 'numeric', 'lte:age_max'],
            'age_max' => ['required', 'numeric', 'gte:age_min'],
            'description' => ['nullable', 'string'],
            'registration_fee' => ['required', 'numeric'],
            'status' => ['required', new Enum(StatusProgram::class)],
        ], [
            'code.regex' => 'Kode tidak boleh mengandung spasi.',
        ], $this->attributes);

        try {
            DB::beginTransaction();
            Program::create([
                'code' => $request->code,
                'name' => strtoupper($request->name),
                'age_min' => $request->age_min,
                'age_max' => $request->age_max,
                'description' => $request->description,
                'registration_fee' => $request->registration_fee,
                'status' => StatusProgram::from($request->status),
            ]);
            DB::commit();
            return redirect()->route('program.index')->with('success', 'Program berhasil ditambahkan');
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
        $this->checkPermission('program.edit');

        $program = Program::findOrFail($id);
        return Inertia::render('program/Edit', [
            'status_programs' => $this->status_programs,
            'program' => $program,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('program.edit');

        $request->validate([
            'code' => ['required', 'max:20', 'unique:program,code,' . $id . ',id', 'regex:/^\S*$/'],
            'name' => ['required', 'string', 'max:255'],
            'age_min' => ['required', 'numeric', 'lte:age_max'],
            'age_max' => ['required', 'numeric', 'gte:age_min'],
            'description' => ['nullable', 'string'],
            'registration_fee' => ['required', 'numeric'],
            'status' => ['required', new Enum(StatusProgram::class)],
        ], [
            'code.regex' => 'Kode tidak boleh mengandung spasi.',
        ], $this->attributes);

        try {
            DB::beginTransaction();
            $program = Program::findOrFail($id);
            $program->update([
                'code' => $request->code,
                'name' => strtoupper($request->name),
                'age_min' => $request->age_min,
                'age_max' => $request->age_max,
                'description' => $request->description,
                'registration_fee' => $request->registration_fee,
                'status' => StatusProgram::from($request->status),
            ]);
            DB::commit();
            return redirect()->route('program.index')->with('success', 'Program berhasil diubah');
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
        $this->checkPermission('program.delete');

        try {
            DB::beginTransaction();
            $program = Program::findOrFail($id);
            $program->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Program berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
