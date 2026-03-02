<?php

namespace Database\Seeders;

use App\Enums\BudgetPeriodTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = DB::table('accounts')
            ->where('name', 'Test')
            ->first();

        $budgets = [
            ['category_slug' => 'makanan', 'amount' => '2000000.0000'],
            ['category_slug' => 'transportasi', 'amount' => '750000.0000'],
            ['category_slug' => 'belanja', 'amount' => '1000000.0000'],
            ['category_slug' => 'hiburan', 'amount' => '500000.0000'],
            ['category_slug' => 'kesehatan', 'amount' => '500000.0000'],
            ['category_slug' => 'pendidikan', 'amount' => '1000000.0000'],
            ['category_slug' => 'hadiah', 'amount' => '300000.0000'],
            ['category_slug' => 'zakat', 'amount' => '250000.0000'],
        ];

        $now = now();
        $periodStart = $now->copy()->startOfMonth();
        $periodEnd = $now->copy()->endOfMonth();

        $insertData = [];
        foreach ($budgets as $budget) {
            $category = DB::table('categories')
                ->where('account_id', $account->id)
                ->where('slug', $budget['category_slug'])
                ->first();

            $insertData[] = [
                'account_id' => $account->id,
                'category_id' => $category->id,
                'amount' => $budget['amount'],
                'period_type' => BudgetPeriodTypeEnum::Monthly->value,
                'period_start' => $periodStart->format('Y-m-d'),
                'period_end' => $periodEnd->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('budgets')->insert($insertData);
    }
}
