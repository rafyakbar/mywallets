<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'happened_at',
        'account_id',
        'category_id',
        'wallet_id',
        'category_name',
        'wallet_name',
        'wallet_balance_before',
        'amount',
        'wallet_balance_after',
        'direction_amount',
        'type',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'happened_at' => 'datetime',
        'amount' => 'decimal:4',
        'wallet_balance_before' => 'decimal:4',
        'wallet_balance_after' => 'decimal:4',
        'direction_amount' => 'decimal:4',
    ];

    /**
     * Get the account that owns the transaction.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the category for the transaction.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the wallet for the transaction.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the debt payments for the transaction.
     */
    public function debtPayments(): HasMany
    {
        return $this->hasMany(DebtPayment::class);
    }

    /**
     * Get the transfer where this transaction is the withdrawal.
     */
    public function withdrawTransfer(): HasOne
    {
        return $this->hasOne(Transfer::class, 'withdraw_transaction_id');
    }

    /**
     * Get the transfer where this transaction is the deposit.
     */
    public function depositTransfer(): HasOne
    {
        return $this->hasOne(Transfer::class, 'deposit_transaction_id');
    }

    /**
     * Get the transfer where this transaction is the fee.
     */
    public function feeTransfer(): HasOne
    {
        return $this->hasOne(Transfer::class, 'fee_transaction_id');
    }
}
