<?php

namespace App\Models;

use App\Enums\StatusAssessment;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessment';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusAssessment::class,
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
