<?php

namespace App\Models;

use App\Enums\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentMatchEvent extends Model
{
    protected $table = 'student_match_event';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'attendance' => Attendance::class,
        ];
    }

    protected $appends = ['attendance_label'];

    public function getAttendanceLabelAttribute(): ?string
    {
        return $this->attendance
            ? strtoupper($this->attendance->label())
            : "BELUM DIISI";
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function matchEvent(): BelongsTo
    {
        return $this->belongsTo(MatchEvent::class, 'match_event_id', 'id');
    }

    public function studentMatchEventAssessments(): HasMany
    {
        return $this->hasMany(StudentMatchEventAssessment::class, 'student_match_event_id', 'id');
    }
}
