<?php

namespace App\Models;

use App\Enums\StatusBilling;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class Billing extends Model
{
    protected $table = 'billing';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusBilling::class,
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

    protected static function booted(): void
    {
        static::creating(function ($billing) {
            if (empty($billing->invoice)) {
                $billing->invoice = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber(): string
    {
        $datePart = now()->format('Ymd');
        $randomPart = strtoupper(Str::random(5));
        return "INV-{$datePart}-{$randomPart}";
    }

    public function billable(): MorphTo
    {
        return $this->morphTo();
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'billing_id', 'id');
    }
}
