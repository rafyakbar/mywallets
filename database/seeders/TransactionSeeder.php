<?php

namespace Database\Seeders;

use App\Enums\TransactionTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = DB::table('accounts')
            ->where('name', 'Test')
            ->first();

        $saldoAwalCategory = DB::table('categories')
            ->where('account_id', $account->id)
            ->where('slug', 'saldo-awal')
            ->first();

        $makananCategory = DB::table('categories')
            ->where('account_id', $account->id)
            ->where('slug', 'makanan')
            ->first();

        $transportasiCategory = DB::table('categories')
            ->where('account_id', $account->id)
            ->where('slug', 'transportasi')
            ->first();

        $hiburanCategory = DB::table('categories')
            ->where('account_id', $account->id)
            ->where('slug', 'hiburan')
            ->first();

        $belanjaCategory = DB::table('categories')
            ->where('account_id', $account->id)
            ->where('slug', 'belanja')
            ->first();

        $dompetWallet = DB::table('wallets')
            ->where('account_id', $account->id)
            ->where('slug', 'cash')
            ->first();

        $transactions = [];

        // Initial balance transaction (income)
        $transactions[] = [
            'happened_at' => now()->subHours(24)->format('Y-m-d H:i:s'),
            'account_id' => $account->id,
            'category_id' => $saldoAwalCategory->id,
            'wallet_id' => $dompetWallet->id,
            'category_name' => $saldoAwalCategory->name,
            'wallet_name' => $dompetWallet->name,
            'wallet_balance_before' => '0.0000',
            'amount' => '5000000.0000',
            'wallet_balance_after' => '0.0000',
            'direction_amount' => '0.0000',
            'type' => TransactionTypeEnum::Transaction->value,
            'description' => 'Initial wallet balance',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Expense transactions
        $expenseTransactions = [
            ['category' => $makananCategory, 'amount' => '45000.0000', 'description' => 'Lunch'],
            ['category' => $transportasiCategory, 'amount' => '25000.0000', 'description' => 'Online ride'],
            ['category' => $hiburanCategory, 'amount' => '120000.0000', 'description' => 'Streaming subscription'],
            ['category' => $belanjaCategory, 'amount' => '200000.0000', 'description' => 'Daily shopping'],
        ];

        $currentBalance = 5000000.00;

        foreach ($expenseTransactions as $expense) {
            $transactions[] = [
                'happened_at' => now()->addHours(rand(1, 23))->format('Y-m-d H:i:s'),
                'account_id' => $account->id,
                'category_id' => $expense['category']->id,
                'wallet_id' => $dompetWallet->id,
                'category_name' => $expense['category']->name,
                'wallet_name' => $dompetWallet->name,
                'wallet_balance_before' => number_format($currentBalance, 4, '.', ''),
                'amount' => $expense['amount'],
                'wallet_balance_after' => '0.0000',
                'direction_amount' => '0.0000',
                'type' => TransactionTypeEnum::Transaction->value,
                'description' => $expense['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $currentBalance -= (float) $expense['amount'];
        }

        DB::table('transactions')->insert($transactions);
    }
}
