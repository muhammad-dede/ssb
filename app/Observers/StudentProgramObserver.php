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

    public function deleting(StudentProgram $studentProgram): void
    {
        $studentProgram->billing()?->delete();
    }

    protected function syncStudentStatus(StudentProgram $studentProgram): void
    {
        $student = $studentProgram->student;

        if (! $student) {
            return;
        }

        $currentProgram = $student->programs()
            ->whereHas('period', fn($q) => $q->where('status', StatusPeriod::ACTIVE))
            ->first();

        $student->update([
            'status' => $currentProgram && $currentProgram->status instanceof StatusStudentProgram
                ? StatusStudent::tryFrom($currentProgram->status->value) ?? StatusStudent::ACTIVE
                : StatusStudent::INACTIVE,
        ]);
    }
}
