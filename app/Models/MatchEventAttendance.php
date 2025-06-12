<?php

namespace App\Models;

use App\Enums\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEventAttendance extends Model
{
    protected $table = 'match_event_attendance';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attendance' => Attendance::class,
        ];
    }

    public function matchEvent(): BelongsTo
    {
        return $this->belongsTo(MatchEvent::class, 'match_event_id', 'id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}
