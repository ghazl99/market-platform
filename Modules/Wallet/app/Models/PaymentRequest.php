<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;
use Modules\Store\Models\Store;

class PaymentRequest extends Model
{
    protected $fillable = [
        'wallet_id',
        'approved_by',
        'original_amount',
        'original_currency',
        'amount_usd',
        'exchange_rate',
        'status',
    ];

    protected $casts = [
        'original_amount' => 'decimal:2',
        'amount_usd' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
    ];

    /**
     * Get the wallet that owns the payment request.
     */
    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get the admin who approved the request.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the user through wallet relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wallet_id', 'id')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->where('wallets.id', $this->wallet_id);
    }

    /**
     * Get the store through wallet relationship.
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'wallet_id', 'id')
            ->join('wallets', 'stores.id', '=', 'wallets.store_id')
            ->where('wallets.id', $this->wallet_id);
    }

    /**
     * Scope for pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
