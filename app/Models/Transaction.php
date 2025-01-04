<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\BankAccount;

class Transaction extends Model

{
    use HasFactory;
    protected $fillable = 
    [
        'account_number', 
        'type', 
        'amount', 
    ];

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }
    public function reçu()
    {
        return $this->hasOne(Bill::class);
    }

}
