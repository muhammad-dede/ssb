<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\StatusStudent;
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
            'status' => StatusStudent::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(StudentProgram::class, 'student_id', 'id');
    }
}
