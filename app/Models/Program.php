<?php

namespace App\Models;

use App\Enums\StatusProgram;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusProgram::class,
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
