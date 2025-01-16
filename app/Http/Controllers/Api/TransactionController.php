<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Service\TransactionService;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Http\Request;

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
    
    public function transferFund(TransactionRequest $transactionRequest)
    {
        $user = $transactionRequest->user();  // Utilise directement l'utilisateur authentifié
        $data = $transactionRequest->validated();

        $fromBankAccount = $user->bankAccount()->first();  // Récupère le premier compte de l'utilisateur
        $toBankAccount = BankAccount::where('account_number', $data['to_account_number'])->first();

        if (!$toBankAccount) {
            return response()->json(['message' => 'Compte bancaire destinataire introuvable.'], 404);
        }

        if ($data['amount'] <= 0) {
            return response()->json(['message' => 'Le montant doit être supérieur à zéro.'], 400);
        }
        return $this->transactionService->transferFunds($fromBankAccount, $toBankAccount, $data['amount']);
    }

    public function getUserTransactions(Request $request)
    {
        $accountNumber= $request->user()->bankAccount()->pluck('account_number');
        return $this->transactionService->getUserTransactions($accountNumber);
    }
    
    public function getAccountTranscript(Request $request)
    {
        $accountNumber= $request->user()->bankAccount()->pluck('account_number');
        return $this->transactionService->getAccountTranscript($accountNumber);
    }
    
    public function getAllTransactions()
    {
        $transaction= $this->transactionService->getAllTransactions();
        return TransactionResource::collection($transaction);
    }

}