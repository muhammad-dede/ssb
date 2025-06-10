<?php

namespace App\Observers;

use App\Enums\StatusPeriod;
use App\Enums\StatusStudent;
use App\Enums\StatusStudentProgram;
use App\Models\StudentProgram;

class StudentProgramObserver
{
    public function saved(StudentProgram $studentProgram): void
    {
        $this->syncStudentStatus($studentProgram);
    }

    public function deleted(StudentProgram $studentProgram): void
    {
        $this->syncStudentStatus($studentProgram);
    }

    protected function syncStudentStatus(StudentProgram $studentProgram): void
    {
        $student = $studentProgram->student;

        if (! $student) {
            return;
        }

        $activeProgram = $student->programs()
            ->where('status', StatusStudentProgram::ACTIVE)
            ->whereHas('period', fn($q) => $q->where('status', StatusPeriod::ACTIVE))
            ->first();

        $student->update([
            'status' => $activeProgram
                ? StatusStudent::ACTIVE
                : StatusStudent::INACTIVE,
        ]);
    }
}
