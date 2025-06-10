<?php

namespace App\Models;

use App\Enums\StatusTraining;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Training extends Model
{
    protected $table = 'training';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusTraining::class,
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
        return $this->hasMany(TrainingAttendance::class, 'training_id', 'id');
    }

    public function assessments(): HasMany
    {
        return $this->hasMany(TrainingAssessment::class, 'training_id', 'id');
    }
}
