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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('happened_at');
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('wallet_id')->constrained('wallets')->onDelete('cascade');
            $table->string('category_name', 255);
            $table->string('wallet_name', 255);
            $table->decimal('wallet_balance_before', 65, 4);
            $table->decimal('amount', 65, 4);
            $table->decimal('wallet_balance_after', 65, 4);
            $table->decimal('direction_amount', 65, 4);
            $table->string('type', 255);
            $table->string('description', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('account_id');
            $table->index('wallet_id');
            $table->index('category_id');
            $table->index('happened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
