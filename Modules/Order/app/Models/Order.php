<?php

namespace Modules\Order\Models;

use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// use Modules\Order\Database\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'store_id',
        'status',
        'payment_status',
        'total_amount',
        'cancel_reason',
    ];
    /**
     * Get created_at in store timezone
     */
    public function getCreatedAtInStoreTimezoneAttribute()
    {
        $timezone = $this->store?->timezone ?? 'UTC';
        return $this->created_at->timezone($timezone);
    }

    /**
     * Get updated_at in store timezone
     */
    public function getUpdatedAtInStoreTimezoneAttribute()
    {
        $timezone = $this->store?->timezone ?? 'UTC';
        return $this->updated_at->timezone($timezone);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(\Modules\User\Models\User::class);
    }

    public function store()
    {
        return $this->belongsTo(\Modules\Store\Models\Store::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(\Modules\Wallet\Models\WalletTransaction::class);
    }
    
}
