<?php

namespace App\Models;

use App\Enums\StatusPeriod;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $table = 'period';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => StatusPeriod::class,
        ];
    }
}
