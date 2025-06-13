<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Gender;
use App\Enums\StatusCoach;
use App\Enums\StatusUser;
use App\Enums\Variant;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class CoachController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $variants = [];
    protected $status_coaches = [];
    protected $genders = [];
    // Validations
    protected $attributes = [
        'name' => 'Nama',
        'place_of_birth' => 'Tempat Lahir',
        'date_of_birth' => 'Tanggal Lahir',
        'gender' => 'Jenis Kelamin',
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'national_id_number' => 'No. Identitas',
        'photo' => 'Foto',
        'coaching_license' => 'Lisensi Kepelatihan',
        'license_number' => 'Nomor Lisensi',
        'license_issued_at' => 'Tanggal Terbit',
        'license_expired_at' => 'Tanggal Berakhir',
        'license_issuer' => 'Lembaga Kepelatihan',
        'email' => 'Email',
        'password' => 'Password',
        'status' => 'Status',
    ];

    public function __construct()
    {
        $this->variants = Variant::options();
        $this->status_coaches = StatusCoach::options();
        $this->genders = Gender::options();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('admin.coach.index');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $coaches = Coach::query()
            ->with(['user'])
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

        $coaches->getCollection()->transform(function ($coach) {
            $coach->photo_url = $coach->photo ? asset('storage/' . $coach->photo) : null;
            return $coach;
        });

        return Inertia::render('admin/coach/Index', [
            'variants' => $this->variants,
            'status_coaches' => $this->status_coaches,
            'coaches' => $coaches,
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
        $this->checkPermission('admin.coach.create');

        return Inertia::render('admin/coach/Create', [
            'status_coaches' => $this->status_coaches,
            'genders' => $this->genders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('admin.coach.create');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', new Enum(Gender::class)],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:255'],
            'national_id_number' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'coaching_license' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255'],
            'license_issued_at' => ['required', 'date'],
            'license_expired_at' => ['required', 'date'],
            'license_issuer' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:user,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'status' => ['required', new Enum(StatusCoach::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => strtoupper($request->name),
                'email' => strtolower($request->email),
                'password' => bcrypt($request->password),
                'status' => StatusUser::ACTIVE,
            ]);
            $user->syncRoles('Coach');
            $coach = Coach::create([
                'name' => strtoupper($request->name),
                'place_of_birth' => strtoupper($request->place_of_birth),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => strtoupper($request->address),
                'phone' => $request->phone,
                'national_id_number' => $request->national_id_number,
                'photo' => null,
                'coaching_license' => $request->coaching_license,
                'license_number' => $request->license_number,
                'license_issued_at' => $request->license_issued_at,
                'license_expired_at' => $request->license_expired_at,
                'license_issuer' => $request->license_issuer,
                'status' => StatusCoach::from($request->status),
                'user_id' => $user->id,
            ]);
            if ($request->hasFile('photo')) {
                $path = Storage::disk('public')->put('coach', $request->photo);
                $coach->update([
                    'photo' => $path,
                ]);
            }
            DB::commit();
            return redirect()->route('admin.coach.show', $coach->id)->with('success', 'Pelatih berhasil ditambahkan');
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
        $this->checkPermission('admin.coach.show');

        $coach = Coach::with(['user'])->findOrFail($id);
        $coach->photo_url = asset('storage/' . $coach->photo);
        return Inertia::render('admin/coach/Show', [
            'variants' => $this->variants,
            'status_coaches' => $this->status_coaches,
            'genders' => $this->genders,
            'coach' => $coach,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('admin.coach.edit');

        $coach = Coach::with(['user'])->findOrFail($id);
        $coach->photo_url = asset('storage/' . $coach->photo);
        return Inertia::render('admin/coach/Edit', [
            'status_coaches' => $this->status_coaches,
            'genders' => $this->genders,
            'coach' => $coach,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('admin.coach.edit');

        $coach = Coach::with('user')->findOrFail($id);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', new Enum(Gender::class)],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:255'],
            'national_id_number' => ['required', 'string', 'max:255'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'coaching_license' => ['required', 'string', 'max:255'],
            'license_number' => ['required', 'string', 'max:255'],
            'license_issued_at' => ['required', 'date'],
            'license_expired_at' => ['required', 'date'],
            'license_issuer' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:user,email,' . $coach->user?->id . ',id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'status' => ['required', new Enum(StatusCoach::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $coach->update([
                'name' => strtoupper($request->name),
                'place_of_birth' => strtoupper($request->place_of_birth),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => strtoupper($request->address),
                'phone' => $request->phone,
                'national_id_number' => $request->national_id_number,
                'coaching_license' => $request->coaching_license,
                'license_number' => $request->license_number,
                'license_issued_at' => $request->license_issued_at,
                'license_expired_at' => $request->license_expired_at,
                'license_issuer' => $request->license_issuer,
                'status' => StatusCoach::from($request->status),
            ]);
            $coach->user->update([
                'name' => strtoupper($request->name),
                'email' => strtolower($request->email),
                'password' => $request->password ? bcrypt($request->password) : $coach->user?->password,
            ]);

            if ($request->hasFile('photo')) {
                if ($coach->photo && Storage::disk('public')->exists($coach->photo)) {
                    Storage::disk('public')->delete($coach->photo);
                }
                $path = Storage::disk('public')->put('coach', $request->photo);
                $coach->update([
                    'photo' => $path,
                ]);
            }
            DB::commit();
            return redirect()->route('admin.coach.show', $coach->id)->with('success', 'Pelatih berhasil diubah');
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
        $this->checkPermission('admin.coach.delete');

        try {
            DB::beginTransaction();
            $coach = Coach::findOrFail($id);
            $user = User::findOrFail($coach->user_id);
            if ($coach->photo && Storage::disk('public')->exists($coach->photo)) {
                Storage::disk('public')->delete($coach->photo);
            }
            $user->delete();
            $coach->delete();
            DB::commit();
            return redirect()->route('admin.coach.index')->with('success', 'Pelatih berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
