<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = DB::table('accounts')
            ->where('name', 'Test')
            ->first();

        $wallets = [
            ['name' => 'Bank UOB', 'slug' => 'bank-uob'],
            ['name' => 'Bank Jago', 'slug' => 'bank-jago'],
            ['name' => 'Bank JATIM', 'slug' => 'bank-jatim'],
            ['name' => 'Bank SeaBank', 'slug' => 'bank-seabank'],
            ['name' => 'Bibit Goals', 'slug' => 'bibit-goals'],
            ['name' => 'GoPay', 'slug' => 'gopay'],
            ['name' => 'ShopeePay', 'slug' => 'shopeepay'],
            ['name' => 'Dompet', 'slug' => 'cash'],
        ];

        $insertData = [];
        foreach ($wallets as $wallet) {
            $insertData[] = [
                'account_id' => $account->id,
                'name' => $wallet['name'],
                'slug' => $wallet['slug'],
                'currency_code' => 'IDR',
                'icon' => null,
                'color' => null,
                'decimal_places' => 2,
                'balance' => '0.0000',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('wallets')->insert($insertData);
    }
}
