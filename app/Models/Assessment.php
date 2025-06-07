<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $table = 'assessment';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }
}
