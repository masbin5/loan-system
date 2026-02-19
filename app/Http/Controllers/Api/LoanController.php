<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Services\LoanService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ApplyLoanRequest;


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
    public function apply(ApplyLoanRequest $request)
    {
        $loan = $this->loanService->createLoan(
            $request->validated(),
            auth()->id()
        );

        return response()->json([
            'message' => 'Loan application submitted successfully',
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

        // Validasi role admin
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $loan = $this->loanService->approveLoan($loan);

        return response()->json([
            'message' => 'Loan approved successfully',
            'data' => $loan
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Loan History (Borrower)
    |--------------------------------------------------------------------------
    */
    public function history()
    {
        $loans = $this->loanService->getUserLoans(auth()->id());

        return response()->json($loans);
    }
}
