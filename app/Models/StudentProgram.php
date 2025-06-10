<?php

namespace App\Models;

use App\Enums\StatusStudentProgram;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class StudentProgram extends Model
{
    protected $table = 'student_program';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusStudentProgram::class,
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_code', 'code');
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function billing(): MorphOne
    {
        return $this->morphOne(Billing::class, 'billable');
    }
}
