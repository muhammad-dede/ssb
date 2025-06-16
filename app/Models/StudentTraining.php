<?php

namespace App\Models;

use App\Enums\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentTraining extends Model
{
    protected $table = 'student_training';

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

    public function training(): BelongsTo
    {
        return $this->belongsTo(Training::class, 'training_id', 'id');
    }

    public function studentTrainingAssessments(): HasMany
    {
        return $this->hasMany(StudentTrainingAssessment::class, 'student_training_id', 'id');
    }
}
