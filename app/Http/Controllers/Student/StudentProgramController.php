<?php

namespace App\Http\Controllers\Student;

use App\Enums\PaymentMethod;
use App\Enums\StatusBankAccount;
use App\Enums\StatusBilling;
use App\Enums\StatusPayment;
use App\Enums\StatusPeriod;
use App\Enums\StatusProgram;
use App\Enums\StatusStudentProgram;
use App\Enums\Variant;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Period;
use App\Models\Program;
use App\Models\Student;
use App\Models\StudentProgram;
use App\Traits\HasPermissionCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StudentProgramController extends Controller
{
    use HasPermissionCheck;

    // Enums
    protected $variants = [];
    protected $status_student_programs = [];
    protected $status_billings = [];
    protected $status_payments = [];
    protected $payment_methods = [];
    // Models
    protected $student;
    protected $period_active;
    protected $periods = [];
    protected $programs = [];
    protected $students = [];
    protected $bank_accounts = [];
    protected $banks = [];
    // Validation
    protected $attributes = [
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
        // Enums
        $this->variants = Variant::options();
        $this->status_student_programs = StatusStudentProgram::options();
        $this->status_billings = StatusBilling::options();
        $this->status_payments = StatusPayment::options();
        $this->payment_methods = PaymentMethod::options();
        // Models
        $this->student = Auth::user()?->student;
        $this->period_active = Period::where('status', StatusPeriod::ACTIVE)->first() ?? null;
        $this->periods = Period::orderBy('id', 'desc')->get();
        $this->programs = Program::where('status', StatusProgram::ACTIVE)->get();
        $this->students = Student::orderBy('id', 'desc')->get();
        $this->bank_accounts = BankAccount::with(['bank'])->where('status', StatusBankAccount::ACTIVE)->get();
        $this->banks = Bank::all();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkPermission('student-menu');

        $search = $request->search;
        $per_page = $request->per_page ?? "5";
        $filter = in_array(strtolower($request->filter), ['asc', 'desc']) ? strtolower($request->filter) : 'desc';

        $student_programs = StudentProgram::query()
            ->with(['program', 'period', 'billing', 'billing.payment'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('program', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('period', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->when($filter, function ($query) use ($filter) {
                $query->orderBy('id', $filter);
            })
            ->where('student_id', $this->student->id)
            ->paginate($per_page)
            ->withQueryString();

        return Inertia::render('student/student-program/Index', [
            'variants' => $this->variants,
            'status_payments' => $this->status_payments,
            'status_billings' => $this->status_billings,
            'status_student_programs' => $this->status_student_programs,
            'period_active' => $this->period_active,
            'student_programs' => $student_programs,
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
        $this->checkPermission('student-menu');

        if (!$this->period_active) {
            return redirect()->route('student.student-program.index');
        }

        $student_age = Carbon::parse($this->student->date_of_birth)->age;
        $program = Program::where('age_min', '<=', $student_age)
            ->where('age_max', '>=', $student_age)
            ->latest()->firstOrFail();

        return Inertia::render('student/student-program/Create', [
            'period_active' => $this->period_active,
            'program' => $program,
            'banks' => $this->banks,
            'bank_accounts' => $this->bank_accounts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkPermission('student-menu');

        $is_payment = filter_var($request->payment, FILTER_VALIDATE_BOOLEAN);

        $request->validate([
            'payment' => ['boolean'],
            'accept' => ['boolean'],
            'receiver_id' => [$is_payment ? 'required' : 'nullable', 'exists:bank_account,id'],
            'sender_bank_code' => [$is_payment ? 'required' : 'nullable', 'exists:bank,code'],
            'sender_account_number' => [$is_payment ? 'required' : 'nullable', 'string', 'max:255'],
            'sender_account_holder_name' => [$is_payment ? 'required' : 'nullable', 'string', 'max:255'],
            'proof_file' => [
                $is_payment ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $student_age = Carbon::parse($this->student->date_of_birth)->age;
            $program = Program::where('age_min', '<=', $student_age)
                ->where('age_max', '>=', $student_age)
                ->latest()->firstOrFail();
            $student_program = StudentProgram::create([
                'student_id' => $this->student?->id,
                'program_code' => $program->code,
                'period_id' => $this->period_active->id,
                'status' => StatusStudentProgram::INACTIVE,
            ]);
            $billing = $student_program->billing()->create([
                'amount' => $program->registration_fee ?? 0,
                'due_date' => now()->addDays(7),
                'status' => $is_payment ? StatusBilling::PAID : StatusBilling::UNPAID,
            ]);
            if ($is_payment) {
                $bank_account = BankAccount::findOrFail($request->receiver_id);
                $payment_data = [
                    'amount' => $program->registration_fee ?? 0,
                    'payment_date' => now(),
                    'method' => "TRANSFER",
                    'receiver_bank_code' => $bank_account->bank_code,
                    'receiver_account_number' => $bank_account->account_number,
                    'receiver_account_holder_name' => $bank_account->account_holder_name,
                    'sender_bank_code' => $request->sender_bank_code,
                    'sender_account_number' => $request->sender_account_number,
                    'sender_account_holder_name' => strtoupper($request->sender_account_holder_name),
                    'reference_number' => $request->reference_number,
                    'notes' => null,
                    'status' => StatusPayment::PENDING,
                ];
                if ($request->hasFile('proof_file')) {
                    $path = Storage::disk('public')->put('payment', $request->file('proof_file'));
                    $payment_data['proof_file'] = $path;
                }
                $billing->payment()->create($payment_data);
            }
            DB::commit();
            return redirect()->route('student.student-program.index')->with('success', 'Registrasi berhasil');
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
        $this->checkPermission('student-menu');

        $student_program = StudentProgram::with(['student', 'program', 'period', 'billing', 'billing.payment', 'billing.payment.receiverBank', 'billing.payment.senderBank'])->where('student_id', $this->student?->id)->where('id', $id)->firstOrFail();
        return Inertia::render('student/student-program/Show', [
            'variants' => $this->variants,
            'status_student_programs' => $this->status_student_programs,
            'status_billings' => $this->status_billings,
            'status_payments' => $this->status_payments,
            'payment_methods' => $this->payment_methods,
            'bank_accounts' => $this->bank_accounts,
            'banks' => $this->banks,
            'student_program' => $student_program,
        ]);
    }
}
