<?php

namespace App\Services;

use App\Models\Loan;

class LoanService
{
    public function createLoan(array $data, $userId)
    {
        return Loan::create([
            'user_id' => $userId,
            'amount' => $data['amount'],
            'duration_month' => $data['duration_month'],
            'interest_rate' => $data['interest_rate'],
            'status' => 'pending',
        ]);
    }

    public function approveLoan(Loan $loan)
    {
        if ($loan->status !== 'pending') {
            throw new \Exception('Loan already processed');
        }

        $loan->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // generate cicilan
        $this->generateInstallments($loan);

        return $loan->load('installments');
    }

    public function getUserLoans($userId)
    {
        return Loan::with('installments')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function generateInstallments(Loan $loan)
    {
        // logic cicilan (sementara dummy)
        return true;
    }
}
