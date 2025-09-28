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
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(\Modules\Wallet\Models\WalletTransaction::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
