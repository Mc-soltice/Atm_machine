<?php
namespace App\Http\Repositories;

use App\Models\BankAccount;

class BankAccountRepository 

{
    public function __construct(BankAccount $bank)
    {
        $this->bank = $bank;
    }

    protected $model;

    public function save($account)
    {
        // return $this->model->create($account);    

        $account->save();
    }

    public function findById($id)
    {
        return BankAccount::find($id);
    }
    public function getAllAccounts()
    {
        // return BankAccount::with('user')->orderBy('id', 'desc')->get();
        return BankAccount::all();
    }
}
