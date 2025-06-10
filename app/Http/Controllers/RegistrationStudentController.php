<?php

namespace App\Http\Controllers;

use App\Enums\PaymentMethod;
use App\Enums\StatusBankAccount;
use App\Enums\StatusBilling;
use App\Enums\StatusPayment;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Enums\Variant;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentProgram;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class RegistrationStudentController extends Controller
{
    use HasPermissionCheck;

    protected $period_active;
    protected $periods = [];
    protected $programs = [];
    protected $students = [];
    protected $status_student_programs = [];
    protected $status_billings = [];
    protected $status_payments = [];
    protected $variants = [];
    protected $bank_accounts = [];
    protected $banks = [];
    protected $payment_methods = [];
    protected $attributes = [
        'student_id' => 'Siswa',
        'program_code' => 'Program Yang Diikuti',
        'period_id' => 'Periode Yang Diikuti',
        // Payment
        'amount' => 'Jumlah Pembayaran',
        'payment_date' => 'Tanggal Pembayaran',
        'method' => 'Metode Pembayaran',
        'receiver_id' => 'Bank Tujuan',
        'sender_bank_code' => 'Bank Pengirim',
        'sender_account_number' => 'No Rekening Pengirim',
        'sender_account_holder_name' => 'Atas Nama Pengirim',
        'proof_file' => 'Bukti Transfer',
        'reference_number' => 'No Referensi',
        'notes' => 'Catatan',
        'status' => 'Status',
    ];

    public function __construct()
    {
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
        $this->programs = Program::where('status', StatusProgram::ACTIVE)->get();
        $this->students = Student::orderBy('id', 'desc')->get();
        $this->status_student_programs = StatusStudentProgram::options();
        $this->status_billings = StatusBilling::options();
        $this->status_payments = StatusPayment::options();
        $this->variants = Variant::options();
        $this->payment_methods = PaymentMethod::options();
        $this->bank_accounts = BankAccount::with(['bank'])->where('status', StatusBankAccount::ACTIVE)->get();
        $this->banks = Bank::all();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('registration-student.index');

        $period_id = (int) ($request->period_id ?? $this->period_active->id);
        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = $request->filter ?? 'desc';

        $student_programs = StudentProgram::query()
            ->with(['student', 'program', 'period', 'billing'])
            ->when($period_id, function ($query) use ($period_id) {
                $query->where('period_id', $period_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('registration-student/Index', [
            'periods' => $this->periods,
            'status_student_programs' => $this->status_student_programs,
            'status_billings' => $this->status_billings,
            'student_programs' => $student_programs,
            'period_id_terms' => $period_id,
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
        $this->checkPermission('registration-student.create');

        return Inertia::render('registration-student/Create', [
            'students' => $this->students,
            'programs' => $this->programs,
            'periods' => $this->periods,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('registration-student.create');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'student_id' => ['required', 'exists:student,id',  Rule::unique('student_program')->where(function ($query) use ($request) {
                return $query->where('period_id', $request->period_id);
            }),],
            'program_code' => ['required', 'exists:program,code'],
        ], [
            'student_id.unique' => 'Siswa sudah terdaftar pada periode tersebut.',
        ], $this->attributes);

        try {
            DB::beginTransaction();
            $student_program = StudentProgram::create([
                'student_id' => $request->student_id,
                'program_code' => $request->program_code,
                'period_id' => $request->period_id,
                'status' => StatusStudentProgram::INACTIVE,
            ]);
            $registration_fee = Program::where('code', $request->program_code)
                ->pluck('registration_fee')
                ->first();
            $student_program->billing()->create([
                'amount' => $registration_fee ?? 0,
                'due_date' => now()->addDays(7),
                'status' => StatusBilling::UNPAID,
            ]);
            DB::commit();
            return redirect()->route('registration-student.show', $student_program->id)->with('success', 'Registrasi Siswa berhasil ditambahkan');
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
        $this->checkPermission('registration-student.show');

        $student_program = StudentProgram::with(['student', 'program', 'period', 'billing', 'billing.payment', 'billing.payment.receiverBank', 'billing.payment.senderBank'])->findOrFail($id);
        return Inertia::render('registration-student/Show', [
            'status_student_programs' => $this->status_student_programs,
            'status_billings' => $this->status_billings,
            'status_payments' => $this->status_payments,
            'variants' => $this->variants,
            'payment_methods' => $this->payment_methods,
            'bank_accounts' => $this->bank_accounts,
            'banks' => $this->banks,
            'student_program' => $student_program,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->checkPermission('registration-student.edit');

        $student_program = StudentProgram::with(['billing'])->findOrFail($id);
        return Inertia::render('registration-student/Edit', [
            'students' => $this->students,
            'programs' => $this->programs,
            'periods' => $this->periods,
            'student_program' => $student_program,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->checkPermission('registration-student.edit');

        $request->validate([
            'period_id' => ['required', 'exists:period,id'],
            'student_id' => ['required', 'exists:student,id', Rule::unique('student_program')->ignore($id)->where(function ($query) use ($request) {
                return $query->where('period_id', $request->period_id);
            }),],
            'program_code' => ['required', 'exists:program,code'],
        ], [
            'student_id.unique' => 'Siswa sudah terdaftar pada periode tersebut.',
        ], $this->attributes);

        try {
            DB::beginTransaction();
            $student_program = StudentProgram::findOrFail($id);
            $student_program->update([
                'student_id' => $request->student_id,
                'program_code' => $request->program_code,
                'period_id' => $request->period_id,
            ]);
            $registration_fee = Program::where('code', $request->program_code)
                ->pluck('registration_fee')
                ->first();
            if ($student_program->billing) {
                $student_program->billing->update([
                    'amount' => $registration_fee ?? 0,
                ]);
            } else {
                $student_program->billing()->create([
                    'amount' => $registration_fee ?? 0,
                    'due_date' => now()->addDays(7),
                    'status' => StatusBilling::UNPAID,
                ]);
            }
            DB::commit();
            return redirect()->route('registration-student.show', $student_program->id)->with('success', 'Registrasi Siswa berhasil diubah');
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
        $this->checkPermission('registration-student.delete');

        try {
            DB::beginTransaction();
            $student_program = StudentProgram::findOrFail($id);
            if ($student_program->billing?->payment?->proof_file && Storage::disk('public')->exists($student_program->billing?->payment?->proof_file)) {
                Storage::disk('public')->delete($student_program->billing?->payment?->proof_file);
            }
            $student_program->delete();
            DB::commit();
            return redirect()->route('registration-student.index')->with('success', 'Registrasi berhasil dihapus');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Store or update a resource in storage.
     */
    public function payment(Request $request, string $student_program_id)
    {
        $this->checkPermission('registration-student.show');

        $student_program = StudentProgram::with(['billing', 'billing.payment', 'student'])->findOrFail($student_program_id);
        $is_edit = filled($student_program->billing->payment);
        $existing_payment = $student_program->billing->payment;
        $request->validate([
            'payment_date' => ['required', 'date'],
            'method' => ['required', 'string', 'in:CASH,TRANSFER'],
            'receiver_id' => [Rule::requiredIf($request->method === 'TRANSFER'), 'nullable', 'exists:bank_account,id'],
            'sender_bank_code' => [Rule::requiredIf($request->method === 'TRANSFER'), 'nullable', 'exists:bank,code'],
            'sender_account_number' => [Rule::requiredIf($request->method === 'TRANSFER'), 'nullable', 'string', 'max:255'],
            'sender_account_holder_name' => [Rule::requiredIf($request->method === 'TRANSFER'), 'nullable', 'string', 'max:255'],
            'proof_file' => [
                Rule::requiredIf(!$is_edit && $request->method === 'TRANSFER'),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048'
            ],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'status' => ['required', new Enum(StatusPayment::class)],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $payment_data = [
                'amount' => $student_program->billing?->amount,
                'payment_date' => $request->payment_date,
                'method' => $request->method,
                'notes' => $request->notes,
                'status' => StatusPayment::from($request->status),
            ];
            if ($request->method === 'TRANSFER') {
                $bank_account = BankAccount::findOrFail($request->receiver_id);
                $payment_data = array_merge($payment_data, [
                    'receiver_bank_code' => $bank_account->bank_code,
                    'receiver_account_number' => $bank_account->account_number,
                    'receiver_account_holder_name' => $bank_account->account_holder_name,
                    'sender_bank_code' => $request->sender_bank_code,
                    'sender_account_number' => $request->sender_account_number,
                    'sender_account_holder_name' => strtoupper($request->sender_account_holder_name),
                    'reference_number' => $request->reference_number,
                ]);
                if ($request->hasFile('proof_file')) {
                    if ($is_edit && $existing_payment?->proof_file && Storage::disk('public')->exists($existing_payment->proof_file)) {
                        Storage::disk('public')->delete($existing_payment->proof_file);
                    }
                    $path = Storage::disk('public')->put('payment', $request->file('proof_file'));
                    $payment_data['proof_file'] = $path;
                }
            } else {
                if ($is_edit && $existing_payment?->proof_file && Storage::disk('public')->exists($existing_payment->proof_file)) {
                    Storage::disk('public')->delete($existing_payment->proof_file);
                }
                $payment_data = array_merge($payment_data, [
                    'receiver_bank_code' => null,
                    'receiver_account_number' => null,
                    'receiver_account_holder_name' => null,
                    'sender_bank_code' => null,
                    'sender_account_number' => null,
                    'sender_account_holder_name' => null,
                    'reference_number' => null,
                    'proof_file' => null,
                ]);
            }
            if ($is_edit) {
                $existing_payment->update($payment_data);
            } else {
                $student_program->billing?->payment()->create($payment_data);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil disimpan');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Update a resource in storage.
     */
    public function paymentStatus(Request $request, string $student_program_id)
    {
        $this->checkPermission('registration-student.show');

        $student_program = StudentProgram::with(['billing', 'billing.payment', 'student'])->findOrFail($student_program_id);
        $request->validate([
            'status' => ['required', new Enum(StatusPayment::class)],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [], $this->attributes);
        try {
            DB::beginTransaction();
            $payment = $student_program->billing->payment;
            $payment->update([
                'status' => StatusPayment::from($request->status),
                'notes' => $request->notes,
            ]);
            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
