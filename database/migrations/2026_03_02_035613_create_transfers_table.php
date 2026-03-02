<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->dateTime('happened_at');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('from_wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('to_wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->foreignId('withdraw_transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('deposit_transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('fee_transaction_id')->nullable()->constrained('transactions')->onDelete('cascade');
            $table->decimal('amount', 65, 4);
            $table->decimal('fee', 65, 4);
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->index('account_id');
            $table->index('from_wallet_id');
            $table->index('to_wallet_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
