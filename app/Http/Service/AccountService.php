<?php
namespace App\Http\Service;
use App\Http\Repositories\bankAccountRepository;



class AccountService
{
    protected $AccountsRepository;
    public function __construct(bankAccountRepository $AccountsRepository)
    {
        $this->AccountsRepository = $AccountsRepository;
    }
    public function getAllAccounts()
    {
        return $this->AccountsRepository->getAllAccounts();
    }
}
