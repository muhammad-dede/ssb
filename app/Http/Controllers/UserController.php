<?php

namespace App\Http\Controllers;

use App\Enums\StatusUser;
use App\Models\User;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use HasPermissionCheck;

    protected $status_users;
    protected $roles;
    protected $attributes = [
        'name' => 'Nama',
        'email' => 'Email',
        'password' => 'Password',
        'role' => 'Role',
    ];

    public function __construct()
    {
        $this->status_users = StatusUser::options();
        $this->roles = Role::whereNotIn('name', ['Super Admin', 'Student', 'Coach'])->get();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('user.index');

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

        return Inertia::render('user/Index', [
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
        $this->checkPermission('user.create');

        return Inertia::render('user/Create', [
            'roles' => $this->roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('user.create');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:user,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'max:255', 'exists:roles,name'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => strtoupper($request->name),
                'email' => strtolower($request->email),
                'password' => bcrypt($request->password),
            ]);
            $user->syncRoles($request->role);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan');
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
        $this->checkPermission('user.edit');

        $user = User::with(['roles'])->findOrFail($id);
        return Inertia::render('user/Edit', [
            'roles' => $this->roles,
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('user.edit');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:user,email,' . $id . ',id'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'max:255', 'exists:roles,name'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->update([
                'name' => strtoupper($request->name),
                'email' => strtolower($request->email),
                'password' => $request->password ? bcrypt($request->password) : $user->password,
            ]);
            $user->syncRoles($request->role);
            DB::commit();
            return redirect()->route('user.index')->with('success', 'Pengguna berhasil diubah');
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
        $this->checkPermission('user.delete');

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

    /**
     * Change Status the specified resource from storage.
     */
    public function status(string $id)
    {
        $this->checkPermission('user.edit');

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            $user->update([
                'status' => $user->status === StatusUser::ACTIVE ? StatusUser::INACTIVE : StatusUser::ACTIVE,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Status Pengguna berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
