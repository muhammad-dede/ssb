<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\StatusPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    protected $table = 'payment';

    protected $guarded = [];
    protected $appends = ['proof_file_url'];

    protected function casts(): array
    {
        return [
            'method' => PaymentMethod::class,
            'status' => StatusPayment::class,
        ];
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

    public function getProofFileUrlAttribute(): ?string
    {
        return ($this->proof_file && Storage::disk('public')->exists($this->proof_file))
            ? asset('storage/' . $this->proof_file)
            : null;
    }
}
