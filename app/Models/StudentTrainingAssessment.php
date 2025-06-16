<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentTrainingAssessment extends Model
{
    protected $table = 'student_training_assessment';

    protected $guarded = [];

    public function studentTraining(): BelongsTo
    {
        return $this->belongsTo(StudentTraining::class, 'student_training_id', 'id');
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class, 'assessment_code', 'code');
    }
}
