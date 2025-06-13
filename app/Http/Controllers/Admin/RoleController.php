<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use HasPermissionCheck;

    protected $attributes = [
        'name' => 'Nama',
        'permissions' => 'Permission',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('admin.role.index');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $roles = Role::query()
            ->withCount('permissions')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->whereNot('name', 'Super Admin')
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('admin/role/Index', [
            'roles' => $roles,
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
        $this->checkPermission('admin.role.create');

        $permissions = Permission::all();
        return Inertia::render('admin/role/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('admin.role.create');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['array'],
        ], [], $this->attributes);
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('admin.role.index')->with('success', 'Role berhasil ditambahkan');
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
        $this->checkPermission('admin.role.edit');

        $role = Role::with(['permissions'])->findOrFail($id);
        if ($role->name === 'Super Admin') return redirect()->back();

        $permissions = Permission::all();
        return Inertia::render('admin/role/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('admin.role.edit');

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name,' . $id . ',id'],
            'permissions' => ['array'],
        ], [], $this->attributes);
        $role = Role::findOrFail($id);
        if ($role->name === 'Super Admin') return abort(403);

        $role->update([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('admin.role.index')->with('success', 'Role berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->checkPermission('admin.role.delete');

        Role::findOrFail($id)->delete();
        return redirect()->route('admin.role.index')->with('success', 'Role berhasil dihapus');
    }
}
