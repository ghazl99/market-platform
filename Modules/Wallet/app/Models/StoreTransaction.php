<?php

namespace Modules\Wallet\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Store\Models\Store;

// use Modules\Wallet\Database\Factories\StoreTransactionFactory;

class StoreTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['store_id', 'type', 'amount', 'order_id', 'status'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function order()
    {
        return $this->belongsTo(\Modules\Order\Models\Order::class);
    }
}
