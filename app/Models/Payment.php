<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\StatusPayment;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    protected $table = 'payment';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'method' => PaymentMethod::class,
            'status' => StatusPayment::class,
        ];
    }

    protected $appends = ['method_label', 'status_label', 'status_variant', 'proof_file_url', 'can_confirm', 'can_edit'];

    public function getMethodLabelAttribute(): string
    {
        return strtoupper($this->method->label());
    }

    public function getStatusLabelAttribute(): string
    {
        return strtoupper($this->status->label());
    }

    public function getStatusVariantAttribute(): string
    {
        return Variant::tryFrom($this->status->value)?->label() ?? 'outline';
    }

    public function getProofFileUrlAttribute(): ?string
    {
        return ($this->proof_file && Storage::disk('public')->exists($this->proof_file))
            ? asset('storage/' . $this->proof_file)
            : null;
    }

    public function getCanConfirmAttribute(): bool
    {
        return $this->status === StatusPayment::PENDING;
    }

    public function getCanEditAttribute(): bool
    {
        return $this->status !== StatusPayment::PAID;
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class, 'billing_id', 'id');
    }

    public function receiverBank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'receiver_bank_code', 'code');
    }

    public function senderBank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'sender_bank_code', 'code');
    }
}
