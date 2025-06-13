<?php

namespace App\Http\Controllers\Student;

use App\Enums\DominantFoot;
use App\Enums\Gender;
use App\Enums\StatusUser;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class EnsureStudentController extends Controller
{
    // Enums
    protected $genders = [];
    protected $dominant_foots = [];
    // Validation
    protected $attributes = [
        'name' => 'Nama',
        'place_of_birth' => 'Tempat Lahir',
        'date_of_birth' => 'Tanggal Lahir',
        'gender' => 'Jenis Kelamin',
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'national_id_number' => 'No. Identitas',
        'photo' => 'Foto',
        'dominant_foot' => 'Kaki Dominan',
        'height_cm' => 'Tinggi Badan',
        'weight_kg' => 'Berat Badan',
    ];

    public function __construct()
    {
        $this->genders = Gender::options();
        $this->dominant_foots = DominantFoot::options();
    }

    public function create()
    {
        $user = Auth::user();
        return Inertia::render('student/ensure-student/Create', [
            'dominant_foots' => $this->dominant_foots,
            'genders' => $this->genders,
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'place_of_birth' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'gender' => ['required', new Enum(Gender::class)],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:255'],
            'national_id_number' => ['required', 'string', 'max:255'],
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'dominant_foot' => ['required', 'string', new Enum(DominantFoot::class)],
            'height_cm' => ['required', 'numeric', 'min:100', 'max:300'],
            'weight_kg' => ['required', 'numeric', 'min:20', 'max:300'],
        ], [], $this->attributes);

        try {
            DB::beginTransaction();
            $user = Auth::user();
            $user->update([
                'name' => strtoupper($request->name),
                'status' => StatusUser::ACTIVE,
            ]);
            $student = Student::create([
                'name' => strtoupper($request->name),
                'place_of_birth' => strtoupper($request->place_of_birth),
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => strtoupper($request->address),
                'phone' => $request->phone,
                'national_id_number' => $request->national_id_number,
                'photo' => null,
                'dominant_foot' => $request->dominant_foot,
                'height_cm' => $request->height_cm,
                'weight_kg' => $request->weight_kg,
                'user_id' => $user->id,
            ]);
            if ($request->hasFile('photo')) {
                $path = Storage::disk('public')->put('student', $request->photo);
                $student->update([
                    'photo' => $path,
                ]);
            }
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Data diri Anda berhasil diperbarui');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
