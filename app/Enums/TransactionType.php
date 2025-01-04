<?php

namespace App\Enums;

enum TransactionType
{
    //
    const Deposit = 'deposit';
    const Withdrawal = 'withdrawal';
    const Transfer = 'transfer';

    public static function getValues(): array
    {
        return [
            self::Deposit,
            self::Withdrawal,
            self::Transfer,
        ];
    }
}

