<?php

namespace Database\Seeders;

use App\Enums\DebtTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DebtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = DB::table('accounts')
            ->where('name', 'Test')
            ->first();

        $dompetWallet = DB::table('wallets')
            ->where('account_id', $account->id)
            ->where('slug', 'cash')
            ->first();

        DB::table('debts')->insert([
            'account_id' => $account->id,
            'wallet_id' => $dompetWallet->id,
            'counterparty_name' => 'Friend',
            'type' => DebtTypeEnum::Payable->value,
            'total_amount' => '1000000.0000',
            'remaining_amount' => '1000000.0000',
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'description' => 'Personal loan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
