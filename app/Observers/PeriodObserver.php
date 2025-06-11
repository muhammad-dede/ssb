<?php

namespace App\Observers;

use App\Enums\StatusPeriod;
use App\Models\Period;

class PeriodObserver
{
    public function saving(Period $period)
    {
        if ($period->status === StatusPeriod::ACTIVE) {
            Period::where('status', StatusPeriod::ACTIVE)
                ->where('id', '!=', $period->id)
                ->update(['status' => StatusPeriod::INACTIVE]);
        }
    }
}
