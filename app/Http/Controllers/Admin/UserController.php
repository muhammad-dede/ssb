<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusUser;
use App\Enums\Variant;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $variants = [];
    protected $status_users = [];
    // Models
    protected $roles = [];
    // Validation
    protected $attributes = [
        'name' => 'Nama',
        'email' => 'Email',
        'password' => 'Password',
        'role' => 'Role',
        'status' => 'Status',
    ];

    public function __construct()
    {
        $this->variants = Variant::options();
        $this->status_users = StatusUser::options();
        $this->roles = Role::whereNotIn('name', ['Super Admin', 'Student', 'Coach'])->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('admin.user.index');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $users = User::query()
            ->with(['roles'])
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Student', 'Coach']);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('admin/user/Index', [
            'variants' => $this->variants,
            'status_users' => $this->status_users,
            'users' => $users,
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
        $this->checkPermission('admin.user.create');

        return Inertia::render('admin/user/Create', [
            'roles' => $this->roles,
            'status_users' => $this->status_users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('admin.user.create');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:user,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'max:255', 'exists:roles,name'],
            'status' => ['required', new Enum(StatusUser::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => strtoupper($request->name),
                'email' => strtolower($request->email),
                'password' => bcrypt($request->password),
                'status' => StatusUser::from($request->status),
            ]);
            $user->syncRoles($request->role);
            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'Pengguna berhasil ditambahkan');
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
        $this->checkPermission('admin.user.edit');

        $user = User::with(['roles'])->findOrFail($id);
        return Inertia::render('admin/user/Edit', [
            'roles' => $this->roles,
            'status_users' => $this->status_users,
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('admin.user.edit');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:user,email,' . $id . ',id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'max:255', 'exists:roles,name'],
            'status' => ['required', new Enum(StatusUser::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->update([
                'name' => strtoupper($request->name),
                'email' => strtolower($request->email),
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                'status' => StatusUser::from($request->status),
            ]);
            $user->syncRoles($request->role);
            DB::commit();
            return redirect()->route('admin.user.index')->with('success', 'Pengguna berhasil diubah');
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
        $this->checkPermission('admin.user.delete');

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Pengguna berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
