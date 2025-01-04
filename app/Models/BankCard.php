<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankCard extends Model
{
    
    protected $fillable = 
    [
        'card_number',
        'status',
        'user_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
