<?php

namespace App\Observers;

use App\Models\Payment;
use App\Enums\StatusBilling;
use App\Enums\StatusPayment;
use App\Enums\StatusStudentProgram;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $this->syncStatus($payment);
    }

    public function updated(Payment $payment): void
    {
        if (! $payment->isDirty('status')) {
            return;
        }

        $this->syncStatus($payment);
    }

    protected function syncStatus(Payment $payment): void
    {
        $billing = $payment->billing;

        if (! $billing) {
            return;
        }

        // Default status untuk StudentProgram
        $statusStudentProgram = StatusStudentProgram::INACTIVE;

        // Update billing status dan tentukan status student program
        switch ($payment->status) {
            case StatusPayment::PAID:
                $billing->update(['status' => StatusBilling::PAID]);
                $statusStudentProgram = StatusStudentProgram::ACTIVE;
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
        }
    }
}
