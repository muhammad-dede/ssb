<?php

namespace App\Models;

use App\Enums\StatusBankAccount;
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

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_code', 'code');
    }
}
