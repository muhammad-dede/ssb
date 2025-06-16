<?php

namespace App\Models;

use App\Enums\StatusBankAccount;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{
    protected $table = 'bank_account';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusBankAccount::class,
        ];
    }

    protected $appends = ['status_label', 'status_variant'];

    public function getStatusLabelAttribute(): string
    {
        return strtoupper($this->status->label());
    }

    public function getStatusVariantAttribute(): string
    {
        return Variant::tryFrom($this->status->value)?->label() ?? 'outline';
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_code', 'code');
    }
}
