<?php

namespace Database\Seeders;

use App\Enums\AccountMemberRoleEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = DB::table('users')
            ->where('email', 'rafy.social@gmail.com')
            ->first();

        $account = DB::table('accounts')
            ->where('name', 'Test')
            ->first();

        DB::table('account_members')->insert([
            'account_id' => $account->id,
            'user_id' => $user->id,
            'role' => AccountMemberRoleEnum::Owner->value,
            'invited_at' => null,
            'confirmed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
