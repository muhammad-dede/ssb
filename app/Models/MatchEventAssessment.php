<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEventAssessment extends Model
{
    protected $table = 'match_event_assessment';

    protected $guarded = [];

    public function matchEvent(): BelongsTo
    {
        return $this->belongsTo(MatchEvent::class, 'match_event_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_code', 'code');
    }
}
