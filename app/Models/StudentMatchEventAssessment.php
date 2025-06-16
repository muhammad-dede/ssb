<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentMatchEventAssessment extends Model
{
    protected $table = 'student_match_event_assessment';

    protected $guarded = [];

    public function studentMatchEvent(): BelongsTo
    {
        return $this->belongsTo(StudentMatchEvent::class, 'student_match_event_id', 'id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_code', 'code');
    }
}
