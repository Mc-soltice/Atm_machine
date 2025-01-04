<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Service\TransactionService;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\TransferResource;
use App\Models\User;
use App\Models\BankAccount;

class TransactionController extends Controller
{
    protected $transactionService;
    
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    public function doTransaction (TransactionRequest $request,$userId)
    {
        $data = $request->validated();

        $user = User::findOrFail($userId);
        
        $bankAccount= $user->bankAccount;
        
        $transaction = $this->transactionService->processTransaction($bankAccount, $data['amount'], transactionType: $data['type']);
        
        return new TransactionResource($transaction);

    }
    
    
    public function transferFund(TransactionRequest $request, $userId)
    {
        $data = $request->validated();

        // Récupérer le compte bancaire de l'utilisateur
        $fromBankAccount = User::findOrFail($userId)->bankAccount;
        
        // Vérifier que le compte bancaire existe
        if (!$fromBankAccount) {
            return response()->json(['message' => 'Compte bancaire introuvable.'], 404);
        }
        
        // Récupérer le numéro de compte du destinataire
        $toBankAccountNumber = $data['to_account_number'];

        // Récupérer le numéro de compte de l'expéditeur
        $fromBankAccountNumber = $fromBankAccount->account_number;
        
        // Récupérer le compte bancaire destinataire
        $toBankAccount = BankAccount::where('account_number', $data['to_account_number'])->first();
        
        // Vérifier que le compte destinataire existe
        if (!$toBankAccount) {
            return response()->json(['message' => 'Compte bancaire destinataire introuvable.'], 404);
        }
        // Vérifier que le montant est valide
        if ($data['amount'] <= 0) {
            return response()->json(['message' => 'Le montant doit être supérieur à zéro.'], 400);
        }
        
        $status = $this->transactionService->transferFunds($fromBankAccount, $toBankAccount, $fromBankAccountNumber, $toBankAccountNumber, $data['amount']);
        
        return response()->json(['status' => $status]);
    }
    public function getAllTransactions()
    {
        $transaction= $this->transactionService->getAllTransactions();
    
        return TransactionResource::collection($transaction);
    }
}