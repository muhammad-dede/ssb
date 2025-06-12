<?php

namespace App\Models;

use App\Enums\StatusMatchEvent;
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

    public function attendances(): HasMany
    {
        return $this->hasMany(MatchEventAttendance::class, 'match_event_id', 'id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(MatchEventAssessment::class, 'match_event_id', 'id');
    }
}
