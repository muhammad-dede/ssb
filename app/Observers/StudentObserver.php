<?php

namespace App\Observers;

use App\Models\Student;

class StudentObserver
{
    public function deleting(Student $student): void
    {
        foreach ($student->programs as $program) {
            $program->billing()?->delete();
        }
    }
}
