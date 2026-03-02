<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'happened_at',
        'account_id',
        'from_wallet_id',
        'to_wallet_id',
        'withdraw_transaction_id',
        'deposit_transaction_id',
        'fee_transaction_id',
        'amount',
        'fee',
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
        'fee' => 'decimal:4',
    ];

    /**
     * Get the account that owns the transfer.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the source wallet.
     */
    public function fromWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'from_wallet_id');
    }

    /**
     * Get the destination wallet.
     */
    public function toWallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'to_wallet_id');
    }

    /**
     * Get the withdrawal transaction.
     */
    public function withdrawTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'withdraw_transaction_id');
    }

    /**
     * Get the deposit transaction.
     */
    public function depositTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'deposit_transaction_id');
    }

    /**
     * Get the fee transaction.
     */
    public function feeTransaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'fee_transaction_id');
    }
}
