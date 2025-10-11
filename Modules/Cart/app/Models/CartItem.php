<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Models\Product;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'player_id',
        'delivery_email',
        'activation_code',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeForUserAndStore($query, int $userId, int $storeId)
    {
        return $query->whereHas('cart', function ($q) use ($userId, $storeId) {
            $q->where('user_id', $userId)
                ->where('store_id', $storeId);
        });
    }
}
