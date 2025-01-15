<?php

namespace App\Http\Repositories;

use App\Models\Transaction;
use App\Models\Logging;

class TransactionRepository 
{
protected $model;

public function __construct(Transaction $transaction)
{
    $this->model = $transaction;
}
  

    public function create($data)
    {
        Logging::store(" Account {$data['account_number']} make {$data['type']} of {$data['amount']} succesfully");
        return Transaction::create($data);
    }
    
    // Sa c'est la éthode pour enregistrer le transfert
    public function createTransferTransaction($fromBankAccountNumber, $toBankAccountNumber, $amount)
    {
        $this->proceedToTransferTransaction($fromBankAccountNumber, $amount, "withdrawal");
        $this->proceedToTransferTransaction($toBankAccountNumber, $amount, "deposit");
        Logging::store(" Account {$fromBankAccountNumber} make a transfer of {$amount} to {$toBankAccountNumber} succesfully");

    }
    private function proceedToTransferTransaction($accountNumber, $amount, $type)
    {
        $this->model->create([
            'account_number' => $accountNumber,
            'amount' => $amount,
            'type' => $type,
        ]);
        
    }

    public function getAllTransactions()
    {

        
        return Transaction::orderBy('created_at', 'desc')->get();
    }

}