<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Store\Models\Store;
use Modules\User\Models\User;

// use Modules\Cart\Database\Factories\CartFactory;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['user_id', 'store_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
