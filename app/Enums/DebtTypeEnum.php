<?php

namespace App\Enums;

enum DebtTypeEnum: string
{
    case Payable = 'payable';
    case Receivable = 'receivable';
}
