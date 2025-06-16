<?php

namespace App\Models;

use App\Enums\StatusMatchEvent;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatchEvent extends Model
{
    protected $table = 'match_event';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusMatchEvent::class,
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

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id', 'id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_code', 'code');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class, 'coach_id', 'id');
    }

    public function studentMatchEvents(): HasMany
    {
        return $this->hasMany(StudentMatchEvent::class, 'match_event_id', 'id');
    }
}
