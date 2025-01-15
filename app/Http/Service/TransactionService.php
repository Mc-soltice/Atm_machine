<?php
namespace App\Http\Service;

use App\Http\Repositories\TransactionRepository;
use App\Models\Transaction;

class TransactionService
{
    protected $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function processTransaction($bankAccount, $amount, $transactionType)
    {
        if ($transactionType === 'withdrawal' && $bankAccount->balance < $amount) {
            
            return response()->json('Insufficient funds for transfer.');
            
        } elseif ($transactionType === 'withdrawal') 
        {
            $bankAccount->balance -= $amount; // Dans le cas d'un retrait, on soustrait le montant de la balance
        } 
        elseif ($transactionType === 'deposit') {
            
            $bankAccount->balance += $amount; // Dans le cas d'un depot le montant seras ajouter a la balance
        }
        $bankAccount->save();

        $transactionData = [
            'account_number' => $bankAccount->account_number,
            'type' => $transactionType,
            'amount' => $amount,
        ];
        return $this->transactionRepository->create($transactionData);
    }


    public function transferFunds($fromBankAccount,$toBankAccount,$fromBankAccountNumber, $toBankAccountNumber, $amount)
    {
        if ($fromBankAccount->balance < $amount) {
            return response('Insufficient funds for transfer.');
        }

        $this->transactionRepository->createTransferTransaction($fromBankAccountNumber, $toBankAccountNumber, $amount);
        
        // je fais la mise à jour les soldes
        $fromBankAccount->balance -= $amount;
        $fromBankAccount->save();
        
        $toBankAccount->balance += $amount;
        $toBankAccount->save();

    return (object) [
        'from_account_number' => $fromBankAccountNumber,
        'from_account_balance' => $fromBankAccount->balance,
        'to_accountNumber' => $toBankAccountNumber,
        'to_account_balance' => $toBankAccount->balance,
        'amount' => $amount,
    ];
    }

    public function getUserTransactions($accountNumber)
    {

        $transactions = Transaction::whereIn('account_number', $accountNumber)
                               ->orderBy('created_at', 'desc')
                               ->get();
        return $transactions;
    }

    public function getAllTransactions()
    {
        return $this->transactionRepository->getAllTransactions();
    }
    
}

