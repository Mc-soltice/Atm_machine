<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'amount' => 'required|numeric',
            'type' => 'required|in:deposit,withdrawal,transfer',
        ];
            if ($this->input('type') === 'transfer') {
                $rules['to_account_number'] = 'required|integer|exists:bank_accounts,account_number'; // Validation du compte de destination
            }
            return $rules;
    }
    
}
