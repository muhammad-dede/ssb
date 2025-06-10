<?php

namespace App\Models;

use App\Enums\StatusProgram;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusProgram::class,
        ];
    }
}
