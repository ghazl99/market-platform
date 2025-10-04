<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
