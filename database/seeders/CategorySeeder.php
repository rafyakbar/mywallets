<?php

namespace Database\Seeders;

use App\Enums\CategoryStatusEnum;
use App\Enums\CategoryTypeEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = DB::table('accounts')
            ->where('name', 'Test')
            ->first();

        $incomeCategories = [
            ['name' => 'Saldo Awal', 'slug' => 'saldo-awal', 'order' => 1],
            ['name' => 'Transfer', 'slug' => 'transfer-income', 'order' => 2],
            ['name' => 'Gaji', 'slug' => 'gaji', 'order' => 3],
            ['name' => 'Usaha', 'slug' => 'usaha', 'order' => 4],
            ['name' => 'Penghasilan Tambahan', 'slug' => 'penghasilan-tambahan', 'order' => 5],
            ['name' => 'Investasi', 'slug' => 'investasi-income', 'order' => 6],
            ['name' => 'Bunga/Riba', 'slug' => 'bunga-riba-income', 'order' => 7],
        ];

        $expenseCategories = [
            ['name' => 'Transfer', 'slug' => 'transfer-expense', 'order' => 1],
            ['name' => 'Makanan', 'slug' => 'makanan', 'order' => 2],
            ['name' => 'Transportasi', 'slug' => 'transportasi', 'order' => 3],
            ['name' => 'Belanja', 'slug' => 'belanja', 'order' => 4],
            ['name' => 'Hiburan', 'slug' => 'hiburan', 'order' => 5],
            ['name' => 'Kesehatan', 'slug' => 'kesehatan', 'order' => 6],
            ['name' => 'Pendidikan', 'slug' => 'pendidikan', 'order' => 7],
            ['name' => 'Hadiah', 'slug' => 'hadiah', 'order' => 8],
            ['name' => 'Investasi', 'slug' => 'investasi-expense', 'order' => 9],
            ['name' => 'Bunga/Riba', 'slug' => 'bunga-riba-expense', 'order' => 10],
            ['name' => 'Zakat', 'slug' => 'zakat', 'order' => 11],
        ];

        $categories = [];

        foreach ($incomeCategories as $category) {
            $categories[] = [
                'account_id' => $account->id,
                'name' => $category['name'],
                'type' => CategoryTypeEnum::Income->value,
                'slug' => $category['slug'],
                'icon' => null,
                'color' => null,
                'order' => $category['order'],
                'status' => CategoryStatusEnum::Active->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($expenseCategories as $category) {
            $categories[] = [
                'account_id' => $account->id,
                'name' => $category['name'],
                'type' => CategoryTypeEnum::Expense->value,
                'slug' => $category['slug'],
                'icon' => null,
                'color' => null,
                'order' => $category['order'],
                'status' => CategoryStatusEnum::Active->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('categories')->insert($categories);
    }
}
