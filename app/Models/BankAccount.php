<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Transaction;
class BankAccount extends Model
{
    use HasFactory;
    protected $fillable = 
    [
        'account_number',
        'balance',
        'type',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function transaction()
    {
        return $this->hasMany(Transaction::class);
    }
}

