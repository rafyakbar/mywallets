<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = DB::table('users')
            ->where('email', 'rafy.social@gmail.com')
            ->first();

        DB::table('accounts')->insert([
            'owner_id' => $user->id,
            'name' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
