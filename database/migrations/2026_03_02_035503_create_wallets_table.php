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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('slug', 255);
            $table->string('currency_code', 255);
            $table->string('icon', 255)->nullable();
            $table->string('color', 255)->nullable();
            $table->unsignedSmallInteger('decimal_places');
            $table->decimal('balance', 65, 4);
            $table->timestamps();
            $table->softDeletes();

            $table->index('account_id');
            $table->unique(['account_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
