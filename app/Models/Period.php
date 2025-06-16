<?php

namespace App\Models;

use App\Enums\StatusPeriod;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'period';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusPeriod::class,
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
}
