<?php

namespace App\Observers;

use App\Models\StudentProgram;

class StudentProgramObserver
{
    public function deleting(StudentProgram $studentProgram): void
    {
        $studentProgram->billing()?->delete();
    }
}
