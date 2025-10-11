<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

// use Modules\Wallet\Database\Factories\WalletTransactionFactory;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['wallet_id', 'order_id', 'paymentRequest_id', 'old_balance', 'new_balance', 'type', 'amount', 'description', 'note'];
    /**
     * Get timezone from wallet->store, default to UTC
     */
    public function getTimezoneAttribute(): string
    {
        return $this->wallet?->store?->timezone ?? 'UTC';
    }

    /**
     * Get created_at in store timezone
     */
    public function getCreatedAtInStoreTimezoneAttribute()
    {
        return $this->created_at?->timezone($this->timezone);
    }

    /**
     * Get updated_at in store timezone
     */
    public function getUpdatedAtInStoreTimezoneAttribute()
    {
        return $this->updated_at?->timezone($this->timezone);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(\Modules\Order\Models\Order::class);
    }
}
