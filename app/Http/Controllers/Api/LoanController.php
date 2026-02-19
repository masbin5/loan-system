<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    protected $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    /*
    |--------------------------------------------------------------------------
    | Apply Loan (Borrower)
    |--------------------------------------------------------------------------
    */

    public function apply(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'duration_month' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
        ]);

        $loan = Loan::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'duration_month' => $request->duration_month,
            'interest_rate' => $request->interest_rate,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Loan application submitted',
            'data' => $loan
        ], 201);
    }

    /*
    |--------------------------------------------------------------------------
    | Approve Loan (Admin Only)
    |--------------------------------------------------------------------------
    */

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);

        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($loan->status !== 'pending') {
            return response()->json([
                'message' => 'Loan already processed'
            ], 400);
        }

        $loan->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Generate Installments
        $this->loanService->generateInstallments($loan);

        return response()->json([
            'message' => 'Loan approved successfully',
            'data' => $loan->load('installments')
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Loan History (Borrower)
    |--------------------------------------------------------------------------
    */

    public function history()
    {
        $loans = Loan::with('installments')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($loans);
    }
}
