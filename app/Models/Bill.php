<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'transaction_id',
        'content'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
