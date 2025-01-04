<?php
namespace App\Http\Repositories;

use App\Models\BankCard;

class BankCardRepository 

{
    public function __construct(BankCard $bank)
    {
        $this->bank = $bank;
    }

    protected $model;

    public function save($card)
    {
        // return $this->model->create($card);    

        $card->save();
    }

    public function findById($id)
    {
        return BankCard::find($id);
    }
    public function getAllCards()
    {
        // return BankAccount::with('user')->orderBy('id', 'desc')->get();
        return BankCard::all();
    }
}
