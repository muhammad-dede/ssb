<?php

namespace App\Models;

use App\Enums\StatusBilling;
use App\Enums\StatusPayment;
use App\Enums\StatusStudentProgram;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $appends = ['status_label', 'status_variant', 'can_delete'];

    public function getStatusLabelAttribute(): string
    {
        if (!$this->status) {
            return '-';
        }
        if ($this->status === StatusStudentProgram::REGISTERED) {
            return strtoupper($this->status->label());
        }
        $billing = $this->billing;
        if (!$billing) {
            return '-';
        }
        $payment = $billing->payment;
        if (!$payment) {
            return strtoupper($billing->status->label());
        }
        return strtoupper($payment->status->label());
    }

    public function getStatusVariantAttribute(): string
    {
        if (!$this->status) {
            return 'outline';
        }
        if ($this->status === StatusStudentProgram::REGISTERED) {
            return Variant::tryFrom($this->status->value)?->label() ?? 'outline';
        }
        $billing = $this->billing;
        if (!$billing) {
            return 'outline';
        }
        $payment = $billing->payment;
        if (!$payment) {
            return Variant::tryFrom($billing->status->value)?->label() ?? 'outline';
        }
        return Variant::tryFrom($payment->status->value)?->label() ?? 'outline';
    }

    public function getCanDeleteAttribute(): bool
    {
        if ($this->status === StatusStudentProgram::REGISTERED) {
            return false;
        }
        $billing = $this->billing;
        if (!$billing) {
            return true;
        }
        if ($billing->status === StatusBilling::PAID) {
            return false;
        }
        $payment = $billing->payment;
        if ($payment && $payment->status === StatusPayment::PAID) {
            return false;
        }
        return true;
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
