<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\StatusCoach;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
