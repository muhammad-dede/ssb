<?php

namespace App\Http\Controllers;

use App\Enums\StatusBankAccount;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class BankAccountController extends Controller
{
    use HasPermissionCheck;

    protected $status_bank_accounts;
    protected $banks;
    protected $attributes = [
        'bank_code' => 'Bank',
        'account_number' => 'Nomor Rekening',
        'account_holder_name' => 'Atas Nama Pemilik',
    ];

    public function __construct()
    {
        $this->status_bank_accounts = StatusBankAccount::options();
        $this->banks = Bank::all();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('bank-account.index');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = $request->filter ?? 'desc';

        $bank_accounts = BankAccount::query()
            ->with(['bank'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('account_number', 'like', '%' . $search . '%')
                        ->orWhere('account_holder_name', 'like', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('bank-account/Index', [
            'status_bank_accounts' => $this->status_bank_accounts,
            'bank_accounts' => $bank_accounts,
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
        $this->checkPermission('bank-account.create');

        return Inertia::render('bank-account/Create', [
            'banks' => $this->banks,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('bank-account.create');

        $request->validate([
            'bank_code' => ['required', 'exists:bank,code'],
            'account_number' => ['required', 'string', 'max:255', 'unique:bank_account,account_number'],
            'account_holder_name' => ['required', 'string', 'max:255'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            BankAccount::create([
                'bank_code' => $request->bank_code,
                'account_number' => $request->account_number,
                'account_holder_name' => strtoupper($request->account_holder_name),
                'status' => StatusBankAccount::ACTIVE,
            ]);
            DB::commit();
            return redirect()->route('bank-account.index')->with('success', 'Akun Bank berhasil ditambahkan');
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
        $this->checkPermission('bank-account.edit');

        $bank_account = BankAccount::findOrFail($id);
        return Inertia::render('bank-account/Edit', [
            'banks' => $this->banks,
            'bank_account' => $bank_account,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'bank_code' => ['required', 'exists:bank,code'],
            'account_number' => ['required', 'string', 'max:255', 'unique:bank_account,account_number,' . $id . ',id'],
            'account_holder_name' => ['required', 'string', 'max:255'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $bank_account = BankAccount::findOrFail($id);
            $bank_account->update([
                'bank_code' => $request->bank_code,
                'account_number' => $request->account_number,
                'account_holder_name' => strtoupper($request->account_holder_name),
            ]);
            DB::commit();
            return redirect()->route('bank-account.index')->with('success', 'Akun Bank berhasil diubah');
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
        $this->checkPermission('bank-account.delete');

        try {
            DB::beginTransaction();
            $bank_account = BankAccount::findOrFail($id);
            $bank_account->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Akun Bank berhasil dihapus');
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
        $this->checkPermission('bank-account.edit');

        try {
            DB::beginTransaction();
            $bank_account = BankAccount::findOrFail($id);
            $bank_account->update([
                'status' => $bank_account->status === StatusBankAccount::ACTIVE ? StatusBankAccount::INACTIVE : StatusBankAccount::ACTIVE,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Status Akun Bank berhasil diubah');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
