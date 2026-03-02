<?php

namespace App\Enums;

enum TransactionTypeEnum: string
{
    case Transaction = 'transaction';
    case Transfer = 'transfer';
}
