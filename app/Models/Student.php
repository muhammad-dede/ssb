<?php

namespace App\Models;

use App\Enums\DominantFoot;
use App\Enums\Gender;
use App\Enums\StatusPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $table = 'student';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'dominant_foot' => DominantFoot::class,
        ];
    }

    protected $appends = ['gender_label', 'dominant_foot_label'];

    public function getGenderLabelAttribute(): string
    {
        return strtoupper($this->gender->label());
    }

    public function getDominantFootLabelAttribute(): string
    {
        return strtoupper($this->dominant_foot->label());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(StudentProgram::class, 'student_id', 'id');
    }

    public function programPeriodActive(): HasOne
    {
        return $this->hasOne(StudentProgram::class, 'student_id', 'id')
            ->whereHas('period', fn($query) => $query->where('status', StatusPeriod::ACTIVE));
    }
}
