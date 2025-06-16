<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\StatusCoach;
use App\Enums\Variant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Coach extends Model
{
    protected $table = 'coach';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'status' => StatusCoach::class,
        ];
    }

    protected $appends = ['gender_label', 'status_label', 'status_variant'];

    public function getGenderLabelAttribute(): string
    {
        return strtoupper($this->gender->label());
    }

    public function getStatusLabelAttribute(): string
    {
        return strtoupper($this->status->label());
    }

    public function getStatusVariantAttribute(): string
    {
        return Variant::tryFrom($this->status->value)?->label() ?? 'outline';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
