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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->string('counterparty_name', 255);
            $table->string('type', 255);
            $table->decimal('total_amount', 65, 4);
            $table->decimal('remaining_amount', 65, 4);
            $table->date('due_date')->nullable();
            $table->string('description', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('account_id');
            $table->index('wallet_id');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
