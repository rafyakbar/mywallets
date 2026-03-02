<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account_id',
        'name',
        'slug',
        'currency_code',
        'icon',
        'color',
        'decimal_places',
        'balance',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:4',
        'decimal_places' => 'integer',
    ];

    /**
     * Get the account that owns the wallet.
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Get the transactions for the wallet.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the debts for the wallet.
     */
    public function debts(): HasMany
    {
        return $this->hasMany(Debt::class);
    }

    /**
     * Get the transfers where this wallet is the source.
     */
    public function outgoingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_wallet_id');
    }

    /**
     * Get the transfers where this wallet is the destination.
     */
    public function incomingTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_wallet_id');
    }
}
