<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyLoanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1000',
            'duration_month' => 'required|integer|min:1',
            'interest_rate' => 'required|numeric|min:0',
        ];
    }
}
