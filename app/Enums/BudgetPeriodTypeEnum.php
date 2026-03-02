<?php

namespace App\Enums;

enum BudgetPeriodTypeEnum: string
{
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Yearly = 'yearly';
}
