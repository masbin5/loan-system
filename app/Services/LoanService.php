<?php

namespace App\Services;

use App\Models\Installment;
use Carbon\Carbon;

class LoanService
{
    public function generateInstallments($loan)
    {
        $principal = $loan->amount;
        $interestRate = $loan->interest_rate / 100;
        $months = $loan->duration_month;

        // Total bunga
        $totalInterest = $principal * $interestRate;

        // Total bayar
        $totalPayment = $principal + $totalInterest;

        // Cicilan per bulan
        $monthlyPayment = $totalPayment / $months;

        for ($i = 1; $i <= $months; $i++) {
            Installment::create([
                'loan_id' => $loan->id,
                'installment_number' => $i,
                'amount' => $monthlyPayment,
                'due_date' => Carbon::now()->addMonths($i),
                'status' => 'unpaid',
            ]);
        }
    }
}
