<?php

namespace App\Actions;

use App\Models\Payment;
use App\Enums\StatusBilling;
use App\Enums\StatusPayment;
use App\Enums\StatusStudent;
use App\Enums\StatusStudentProgram;

class UpdatePaymentStatusEffect
{
    public static function handle(Payment $payment, StatusPayment $status): void
    {
        $payment->update(['status' => $status]);

        $billing = $payment->billing;

        if (!$billing) {
            return;
        }

        // Default status
        $statusStudentProgram = StatusStudentProgram::INACTIVE;
        $statusStudent = StatusStudent::INACTIVE;

        // Update billing status
        switch ($status) {
            case StatusPayment::PAID:
                $billing->update(['status' => StatusBilling::PAID]);
                $statusStudentProgram = StatusStudentProgram::ACTIVE;
                $statusStudent = StatusStudent::ACTIVE;
                break;

            case StatusPayment::PENDING:
                $billing->update(['status' => StatusBilling::PAID]);
                break;

            case StatusPayment::UNPAID:
                $billing->update(['status' => StatusBilling::UNPAID]);
                break;

            case StatusPayment::FAILED:
                $billing->update([
                    'status' => $billing->status ?? StatusBilling::UNPAID,
                ]);
                break;

            case StatusPayment::EXPIRED:
                $billing->update(['status' => StatusBilling::OVERDUE]);
                break;

            case StatusPayment::CANCELLED:
                $billing->update(['status' => StatusBilling::UNPAID]);
                break;
        }

        $studentProgram = $billing->billable;

        if ($studentProgram instanceof \App\Models\StudentProgram) {
            $studentProgram->update(['status' => $statusStudentProgram]);

            $student = $studentProgram->student;
            if ($student) {
                $student->update(['status' => $statusStudent]);
            }
        }
    }
}
