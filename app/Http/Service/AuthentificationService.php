<?php
namespace App\Http\Service;

use App\Http\Repositories\AuthentificationRepository;
use App\Http\Repositories\BankAccountRepository;
use App\Http\Repositories\BankCardRepository;
use App\Models\BankAccount;
use App\Models\BankCard;
use App\Models\Logging;


class AuthentificationService
{

    protected $authentificationRepository,$bankAccountRepository,$bankCardRepository;

    public function __construct(
        AuthentificationRepository $authentificationRepository,
        BankAccountRepository $bankAccountRepository,
        BankCardRepository $bankCardRepository
        )
    {
        $this->authentificationRepository = $authentificationRepository;
        $this->bankAccountRepository = $bankAccountRepository;
        $this->bankCardRepository = $bankCardRepository;
    }

    public function register($request){

        $data=$request->validated();
        $user = $this->authentificationRepository->register($data);


        // Générer un identifiant de compte bancaire unique (5 chiffres)

        $account_number = str_pad(rand(000, 99999), 5, '0', STR_PAD_LEFT);        
        $account = new BankAccount();
        $account->user_id = $user->id;
        $account->balance = 0; // solde initial
        $account->account_number = $account_number; // Assigner le numero random a 5 chiffres
        $this->bankAccountRepository->save($account);
        
        
        // Créer la carte bancaire associée
        
        $card_number = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);   
        $card = new BankCard();     
        $card->user_id = $user->id;
        $card->card_number = $card_number; // Assigner le numero random a 8 chiffres
        $this->bankCardRepository->save($card);
        return $user;
    }

    public function loginUser($request)
    {
        $data = 
        [
            'email' => $request->email,
            'password' => $request->password,
        ];
        
        return $this->authentificationRepository->loginAdminUser($data);   
        
    }

    public function logout($data)
    {
        Logging::store("User {$data['email']} logout succesffully");
         return $data->user()->currentAccessToken()->delete();
    }
    
}   
